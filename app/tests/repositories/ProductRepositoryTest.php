<?php

use Mockery as m;

class ProductRepositoryTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

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
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('product_with_details');

        $repo = new ProductRepository;

        // Search for all
        $result = $repo->search();
        $this->assertEquals(Product::all()->toArray(), $result->toArray());

        // Search by name
        $result = $repo->search( 'Coisa' );
        $equivalentQuery = ['name'=> new \MongoRegex('/^Coisa/i')];
        $this->assertEquals(Product::where($equivalentQuery)->toArray(), $result->toArray());
    }

    public function testShouldGetPageCount()
    {
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('another_valid_product');
        testProductProvider::saved('product_with_details');

        $repo = new ProductRepository;
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
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('another_valid_product');
        testProductProvider::saved('product_with_details');

        $repo = new ProductRepository;
        
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
        $repo = new ProductRepository;

        // A valid instance
        $product = testProductProvider::instance( 'simple_valid_product' );

        $this->assertTrue($repo->createNew( $product ));
        $this->assertNotEquals(null, $product->_id);

        // A invalid instance
        $product = testProductProvider::instance( 'simple_invalid_product' );

        $this->assertFalse($repo->createNew( $product ));
        $this->assertEquals(null, Product::first($product->_id));
    }
    
    public function testShouldGetFirst()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $repo = new ProductRepository;

        $this->assertEquals(Product::first($product->_id), $repo->first($product->_id));
    }

    public function testShouldUpdateInstance()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $product->name = "Bacon";
        $repo = new ProductRepository;

        $this->assertTrue($repo->update($product));

        $product = Product::first($product->_id);

        $this->assertEquals("Bacon", $product->name);
    }

    public function testShouldUpdateCharacteristicsOfInstance()
    {
        $product = testProductProvider::saved('simple_valid_product');
        
        // These are the characteristics defined into the category of
        // the 'simple_valid_product'. See the providers for more detail
        $input = [
            'capacidade' => 25,
            'cor' => 'Preto',
        ];

        $repo = new ProductRepository;

        $this->assertTrue($repo->updateCharacteristics($product, $input));

        $product = Product::first($product->_id);

        $this->assertEquals(25, array_get($product->details, 'capacidade'));
        $this->assertEquals('Preto', array_get($product->details, 'cor'));
    }

    public function testShouldReturnProductsToOption()
    {
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('product_with_details');

        $repo = new ProductRepository;
        $cursor = Product::all();

        $result = $repo->toOptions( $cursor );

        $should_be = array();
        foreach ($cursor as $product) {
            $should_be[$product->_id] = $product->_id.' - '.$product->name;
        }

        $this->assertEquals($should_be, $result);
    }
}
