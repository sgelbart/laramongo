<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ProductTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
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

        // Save and retreive the saved product
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
        $product = f::instance('Product');
        $this->assertFalse($product->isVisible());

        // A valid saved product should be visible
        $product = f::create('Product');
        $this->assertTrue($product->isVisible());

        // A invalid product should not be visible
        $product->state = 'invalid';
        $this->assertFalse($product->isVisible());

        // A deactivated product should not be visible
        $product = f::create('Product', ['deactivated'=>1]);
        $this->assertFalse($product->isVisible());
    }

    /**
     * Should deactivate a product
     */
    public function testShouldDeactivateProduct()
    {
        $product = f::instance('Product');
        $product->deactivate();

        $this->assertTrue($product->deactivated);
    }

    /**
     * Should activate a product
     */
    public function testShouldActivateProduct()
    {
        $product = f::instance('Product', ['deactivated'=>true]);
        $product->activate();

        $this->assertNotEquals(true, $product->deactivated);
    }

    /**
     * Should save invalid product but mark it as invalid
     *
     */
    public function testShouldSaveInvalidProductIfForced()
    {
        $product = new Product;

        $product->name = '';
        $product->category = f::create('Category')->_id;

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
            'id' => 123123,
            'name' => 'thename',
            'nonexistant' => 'avalue',
            'category' => f::create('Category')->_id,
        );

        $document->fillable = array('name','category');

        $document->fill( $input );

        $this->assertNotEquals( $input['id'], $document->getAttribute('id') );
        $this->assertEquals   ( $input['name'], $document->getAttribute('name') );
        $this->assertNotEquals( $input['nonexistant'], $document->getAttribute('nonexistant') );
        $this->assertEquals   ( $input['category'], $document->getAttribute('category') );
    }
}
