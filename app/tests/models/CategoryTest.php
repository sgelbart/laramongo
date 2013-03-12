<?php

class CategoryTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    /**
     * Should validate category
     *
     */
    public function testShouldValidateCategory()
    {
        $category = new Category;

        $category->name = 'validname';

        // Should return true, since it's a valid category
        $this->assertTrue( $category->isValid() );

        $category->name = '';

        // Should return false, since the name is empty
        $this->assertFalse( $category->isValid() );
    }

    /**
     * Should save valid category
     *
     */
    public function testShouldSaveValidCategory()
    {
        $category = new Category;

        $category->name = 'avalidname';

        // Should return true, since it's a valid category
        $this->assertTrue( $category->save() );
    }
}
