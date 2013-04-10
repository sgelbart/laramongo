<?php

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

        $connection = new Zizaco\Mongoloid\MongoDbConnector;
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
}
