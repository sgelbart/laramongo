<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;
use Mockery as m;

class ProductTest extends Zizaco\TestCases\TestCase
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
        $this->cleanCollection( 'tags' );
    }

    /**
     * Mockery teardown
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Should save valid product
     *
     */
    public function testShouldSaveValidProduct()
    {
        $product = testProductProvider::instance('simple_valid_product');

        // Should return true, since it's a valid product
        $this->assertTrue( $product->save() );
    }

    /**
     * Should set 'lm' attribute with a STRING containing the '_id'
     *
     */
    public function testShouldSetLmString()
    {
        $product = testProductProvider::instance('simple_valid_product');

        // Save and retrieve the saved product
        $product->save();
        $product = Product::first($product->_id);

        // Check if the lm attribute has been created
        $this->assertEquals($product->_id, $product->lm);
        $this->assertTrue(is_string($product->lm));
    }

    /**
     * Should not save invalid product
     *
     */
    public function testShouldNotSaveInvalidProduct()
    {
        $product = testProductProvider::instance('simple_invalid_product');

        // Should return true, since it's a valid product
        $this->assertFalse( $product->save() );
    }

    /**
     * Assert if a product is visible or not. A visible product
     * consists in the following:
     * - state is not 'invalid'
     * - deactivate is not any sort of 'true'
     * - product has an _id
     */
    public function testProductVisibility()
    {
        // A non-saved product should not be visible
        $product = testProductProvider::instance('simple_valid_product');
        $this->assertFalse($product->isVisible());

        // A valid saved product should be visible
        $product = testProductProvider::saved('simple_valid_product');
        $this->assertTrue($product->isVisible());

        // A invalid product should not be visible
        $product->state = 'invalid';
        $this->assertFalse($product->isVisible());

        // A deactivated product should not be visible
        $product = testProductProvider::saved('simple_deactivated_product');
        $this->assertFalse($product->isVisible());
    }

    /**
     * Should deactivate a product
     */
    public function testShouldDeactivateProduct()
    {
        $product = testProductProvider::instance('simple_valid_product');
        $product->deactivate();

        $this->assertTrue($product->deactivated);
    }

    /**
     * Should activate a product
     */
    public function testShouldActivateProduct()
    {
        $product = testProductProvider::instance('simple_deactivated_product');
        $product->activate();

        $this->assertNotEquals(true, $product->deactivated);
    }

    /**
     * Should save invalid product but mark it as invalid
     *
     */
    public function testShouldSaveInvalidProductIfForced()
    {
        $product = testProductProvider::instance('simple_invalid_product');

        // Should return true, since it's a valid product
        $this->assertTrue( $product->save(true) );

        $product = Product::find($product->_id);

        $this->assertEquals('invalid', $product->state);
    }

    /**
     * Should not set attributes that are not
     * especified in massAssignment.
     *
     */
    public function testShouldNotSetAnyAttributes()
    {
        $document = new Product;

        $input = array(
            '_id' => 123123,
            'name' => 'thename',
            'nonexistant' => 'avalue',
            'category' => testCategoryProvider::saved('valid_leaf_category')->_id,
        );

        $document->fillable = array('name','category');

        $document->fill( $input );

        $this->assertNotEquals( $input['_id'], $document->getAttribute('id') );
        $this->assertEquals   ( $input['name'], $document->getAttribute('name') );
        $this->assertNotEquals( $input['nonexistant'], $document->getAttribute('nonexistant') );
        $this->assertEquals   ( $input['category'], $document->getAttribute('category') );
    }

    /**
     * Tests if a product renders a pop over
     */
    public function testShouldRenderPopover()
    {
        $product = testProductProvider::instance('simple_valid_product');

        $this->assertContains('<div',$product->renderPopover());
        $this->assertContains('<span',$product->renderPopover());
        $this->assertContains('bacon',$product->renderPopover('bacon'));
    }

    public function testShouldIndexProductOnSave()
    {
        // Enable search engine
        Config::set('search_engine.enabled', true);
        Config::set('search_engine.engine', 'mockedSearchEngine');

        // Prepare mocked searchEngine
        $mockedSearchEng = m::mock('Es');
        $mockedSearchEng->shouldReceive('indexObject')->times(4);

        App::bind('mockedSearchEngine', function() use ($mockedSearchEng){
            return $mockedSearchEng; 
        });

        $product = testProductProvider::instance('simple_valid_product');
        $product->save(); // This should call the SearchEngine->indexObject

        Config::set('search_engine.enabled', false);
    }
}
