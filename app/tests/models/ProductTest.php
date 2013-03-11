<?php

class ProductTest extends TestCase {

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );

        $this->aExistentCategory();
    }

    /**
     * Should validate product
     *
     */
    public function testShouldValidateProduct()
    {
        $product = new Product;

        $product->name = 'product';
        $product->family = 'existentfamily';

        // Should return true, since it's a valid product
        $this->assertTrue( $product->isValid() );

        $product->name = '';
        $product->family = 'existentfamily';

        // Should return false, since the name is empty
        $this->assertFalse( $product->isValid() );

        $product->name = 'product';
        $product->family = 'non_existant';

        // Should return false, since the name is empty
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
        $product->family = 'existentfamily';

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
        $product->family = 'existentfamily';

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
            'family' => 'existentfamily',
        );

        $document->massAssignment = array('name','family');

        $document->setAttributes( $input );

        $this->assertNotEquals( $input['id'], $document->getAttribute('id') );
        $this->assertEquals   ( $input['name'], $document->getAttribute('name') );
        $this->assertNotEquals( $input['nonexistant'], $document->getAttribute('nonexistant') );
        $this->assertEquals   ( $input['family'], $document->getAttribute('family') );
    }


    /**
     * Clean database collection
     */
    private function cleanCollection( $collection )
    {
        $db = LMongo::connection();
        $db->$collection->drop();
    }

    /**
     * Returns an category that "exists in database".
     *
     * @param string $name
     * @return Category
     */
    private function aExistentCategory( $name = 'existentfamily' )
    {
        $category = new Category;

        $category->name = $name;
        $category->description = 'somedescription';
        $category->image = 'aimage.jpg';

        $category->save();

        return $category;
    }
    
}
