<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as FactoryMuff;

class ProductTest extends TestCase {

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );

        $this->existentCategory = FactoryMuff::create('Category');
    }

    /**
     * Should validate product
     *
     */
    public function testShouldValidateProduct()
    {
        $product = new Product;

        $product->name = 'product';
        $product->category = $this->existentCategory->id;

        // Should return true, since it's a valid product
        $this->assertTrue( $product->isValid() );

        $product->name = '';
        $product->category = $this->existentCategory->id;

        // Should return false, since the name is empty
        $this->assertFalse( $product->isValid() );

        $product->name = 'product';
        unset($product->category);

        // Should return false, since the category is absent
        $this->assertFalse( $product->isValid() );
    }

    /**
     * Should save valid product
     *
     */
    public function testShouldSaveValidProduct()
    {
        $product = new Product;

        $product->name = 'product';
        $product->category = $this->existentCategory->id;

        // Should return true, since it's a valid product
        $this->assertTrue( $product->save() );
    }

    /**
     * Should NOT save invalid product
     *
     */
    public function testShouldNotSaveInvalidProduct()
    {
        $product = new Product;

        $product->name = '';
        $product->category = $this->existentCategory->id;

        // Should return true, since it's a valid product
        $this->assertFalse( $product->save() );
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
            'family' => $this->existentCategory->id,
        );

        $document->massAssignment = array('name','family');

        $document->setAttributes( $input );

        $this->assertNotEquals( $input['id'], $document->getAttribute('id') );
        $this->assertEquals   ( $input['name'], $document->getAttribute('name') );
        $this->assertNotEquals( $input['nonexistant'], $document->getAttribute('nonexistant') );
        $this->assertEquals   ( $input['family'], $document->getAttribute('family') );
    }
}
