<?php

use Mockery as m;

class ContentRepositoryTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'contents' );
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }

    public function testShouldSearch()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;

        // Search for all
        $result = $repo->search();
        $this->assertEquals(Content::all()->toArray(), $result->toArray());

        // Search by name
        $result = $repo->search( 'Materia' );
        $equivalentQuery = ['name'=> new \MongoRegex('/^Materia/i')];
        $this->assertEquals(Content::where($equivalentQuery)->toArray(), $result->toArray());
    }

    public function testShouldGetPageCount()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;
        $result = $repo->search();

        $repo->perPage = 6;
        $this->assertEquals(1, $repo->pageCount($result));

        $repo->perPage = 2;
        $this->assertEquals(2, $repo->pageCount($result));

        $repo->perPage = 1;
        $this->assertEquals(3, $repo->pageCount($result));
    }

    public function testShouldPaginate()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;
        
        // 6 per page, first page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 6;
        $page = 1;
        $should_be = $testingResult
            ->limit(6)
            ->skip( ($page-1)*6 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 2 per page, first page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 2;
        $page = 1;
        $should_be = $testingResult
            ->limit(2)
            ->skip( ($page-1)*2 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 2 per page, second page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 2;
        $page = 2;
        $should_be = $testingResult
            ->limit(2)
            ->skip( ($page-1)*2 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 1 per page, second page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 1;
        $page = 2;
        $should_be = $testingResult
            ->limit(1)
            ->skip( ($page-1)*1 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());
    }

    public function testShouldCreateNew()
    {
        $repo = new ContentRepository;

        // A valid instance
        $content = testContentProvider::instance( 'valid_article' );
        unset( $content->_id );

        $this->assertTrue($repo->createNew( $content ));
        $this->assertNotEquals(null, $content->_id);

        // A invalid instance
        $content = testContentProvider::instance( 'invalid_article' );
        unset( $content->_id );

        $this->assertFalse($repo->createNew( $content ));
        $this->assertEquals(null, $content->_id);
    }

    public function testShouldCreateNewAndAttachImage()
    {
        $repo = new ContentRepository;

        // A valid instance
        $content = testContentProvider::instance( 'valid_image' );
        unset( $content->_id );

        // A mocked image that should be attached
        $image = m::mock('UploadedFile');
        $image->shouldReceive('move')->once();

        // Pass the image as the seconds parameter
        $this->assertTrue($repo->createNew( $content, $image ));
        $this->assertNotEquals(null, $content->_id);
    }

    public function testShouldFindBySlug()
    {
        $article1 = testContentProvider::saved('valid_article');
        $article2 = testContentProvider::saved('invalid_article');

        $repo = new ContentRepository;

        $this->assertEquals(
            Content::first(['slug'=>$article1->slug]),
            $repo->findBySlug( $article1->slug )
        );

        $this->assertEquals(
            Content::first(['slug'=>$article2->slug]),
            $repo->findBySlug( $article2->slug )
        );

        $this->assertEquals(
            Content::first(['slug'=>'non_existent']),
            $repo->findBySlug( 'non_existent' )
        );
    }

    public function testShouldGetExistentTags()
    {
        $article1 = testContentProvider::saved('valid_article');
        $repo = new ContentRepository;

        $connection = new Zizaco\Mongolid\MongoDbConnector;
        $db = $connection->getConnection()->db;

        // Query for all
        $retrievedTags = $repo->existentTags('');      

        $tagCount = count($retrievedTags);
        $this->assertGreaterThan(1, $tagCount);

        foreach ($retrievedTags as $tag) {
            $this->assertNotNull( $db->tags->findOne(['_id'=>$tag['label']]) );
        }

        // Query for some
        $retrievedTags = $repo->existentTags('intere');

        $this->assertLessThan($tagCount, count($retrievedTags));

        foreach ($retrievedTags as $tag) {
            $this->assertNotNull( $db->tags->findOne(['_id'=>$tag['label']]) );
        }
    }

    public function testShouldGetFirst()
    {
        $article = testContentProvider::saved('valid_article');
        $repo = new ContentRepository;

        $this->assertEquals(Content::first($article->_id), $repo->first($article->_id));
    }

    public function testShouldUpdateInstance()
    {
        $article = testContentProvider::saved('valid_article');
        $article->name = "Bacon";
        $repo = new ContentRepository;

        $this->assertTrue($repo->update($article));

        $article = Content::first($article->_id);

        $this->assertEquals("Bacon", $article->name);
    }

    public function testShouldUpdateAndAttachImageToInstance()
    {
        $article = testContentProvider::saved('valid_image');
        $article->name = "Bacon";
        $repo = new ContentRepository;

        // A mocked image that should be attached
        $image = m::mock('UploadedFile');
        $image->shouldReceive('move')->once();

        $this->assertTrue($repo->update($article, $image));

        $article = Content::first($article->_id);

        $this->assertEquals("Bacon", $article->name);
    }

    public function testShouldRelateToProduct()
    {
        $article = testContentProvider::saved('valid_article');
        $product = testProductProvider::saved( 'simple_valid_product' );

        $repo = new ContentRepository;

        $this->assertTrue($repo->relateToProduct( $article, $product->_id ));

        $article = Content::first($article->_id);

        $this->assertContains($product->_id, $article->products);
    }

    public function testShouldRelateToProductList()
    {
        $article = testContentProvider::saved('valid_article');
        $product1 = testProductProvider::saved( 'simple_valid_product' );
        $product2 = testProductProvider::saved( 'simple_deactivated_product' );
        $product3 = testProductProvider::saved( 'product_with_details' );

        // Makes a string of ids. Ex: "8314242,8377324,8342242"
        $productList = $product1->_id.','.$product2->_id.','.$product3->_id;

        $repo = new ContentRepository;

        $this->assertTrue($repo->relateToProduct( $article, $productList ));

        $article = Content::first($article->_id);

        $this->assertContains($product1->_id, $article->products);
        $this->assertContains($product2->_id, $article->products);
        $this->assertContains($product3->_id, $article->products);
    }

    public function testShouldUnRelateToProduct()
    {
        $article = testContentProvider::saved('valid_article');
        $product = testProductProvider::saved( 'simple_valid_product' );

        $repo = new ContentRepository;

        $repo->relateToProduct( $article, $product->_id );
        $this->assertTrue($repo->removeProduct( $article, $product->_id ));

        $article = Content::first($article->_id);

        $this->assertNotContains($product->_id, $article->products);
    }

    public function testShouldRelateToCategory()
    {
        $article = testContentProvider::saved('valid_article');
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $repo = new ContentRepository;

        $this->assertTrue($repo->relateToCategory( $article, $category->_id ));

        $article = Content::first($article->_id);

        $this->assertContains((string)$category->_id, $article->categories);
    }

    public function testShouldUnRelateToCategory()
    {
        $article = testContentProvider::saved('valid_article');
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $repo = new ContentRepository;

        $repo->relateToCategory( $article, $category->_id );
        $this->assertTrue($repo->removeCategory( $article, $category->_id ));

        $article = Content::first($article->_id);

        $this->assertNotContains((string)$category->_id, $article->categories);
    }

    public function testShouldTagProductToImage()
    {
        $image = testContentProvider::saved('valid_image');
        $product = testProductProvider::saved( 'simple_valid_product' );

        $repo = new ContentRepository;
        $repo->relateToProduct( $image, $product->_id );
        $image = Content::first($image->_id);

        $this->assertTrue($repo->tagToProduct( $image, $product, 10, 20));
        $image = Content::first($image->_id);

        // Asserts if the tag was created
        $this->assertEquals($image->tagged[0]['x'], 10);
        $this->assertEquals($image->tagged[0]['y'], 20);
        $this->assertEquals($image->tagged[0]['product'], $product->_id);
    }

    public function testShouldUntagProductOfImage()
    {
        $image = testContentProvider::saved('valid_image');
        $product = testProductProvider::saved( 'simple_valid_product' );

        $repo = new ContentRepository;
        $repo->relateToProduct( $image, $product->_id );
        $repo->tagToProduct( $image, $product, 10, 20);
        $image = Content::first($image->_id);

        $this->assertTrue($repo->removeTagged( $image, $image->tagged[0]['_id']));

        // Asserts if the tag was removed
        $this->assertFalse(isset( $image->tagged[0] ));
    }
}
