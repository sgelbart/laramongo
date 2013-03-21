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
        $product = new Product;

        $product->name = 'product';
        $product->category = f::create('Category')->_id;

        // Should return true, since it's a valid product
        $this->assertTrue( $product->save() );
    }

    /**
     * Should set 'lm' attribute with a STRING containing the '_id'
     *
     */
    public function testShouldSetLmString()
    {
        $product = new Product;

        $product->_id = '777';
        $product->name = 'product';
        $product->category = f::create('Category')->_id;

        // Save and retreive the saved product
        $product->save();
        $product = Product::first($product->_id);

        // Check if the lm attribute has been created
        $this->assertEquals('777', $product->lm);
        $this->assertTrue(is_string($product->lm));
    }

    /**
     * Should not save invalid product
     *
     */
    public function testShouldNotSaveInvalidProduct()
    {
        $product = new Product;

        $product->name = '';
        $product->category = f::create('Category')->_id;

        // Should return true, since it's a valid product
        $this->assertFalse( $product->save() );
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
