<?php

use Mockery as m;

class CategoryTest extends TestCase {

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
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

    /**
     * Should NOT save invalid category
     *
     */
    public function testShouldNotSaveInvalidCategory()
    {
        $category = new Category;

        $category->name = '';

        // Should return true, since it's a valid category
        $this->assertFalse( $category->save() );
    }

    /**
     * Should not set attributes that are not
     * especified in massAssignment.
     *
     */
    public function testShouldNotSetAnyAttributes()
    {
        $category = new Category;

        $input = array(
            'id' => 123123,
            'name' => 'thename',
            'nonexistant' => 'avalue',
            'description' => 'adescription',
        );

        $category->setAttributes( $input );

        $this->assertNotEquals( $input['id'], $category->getAttribute('id') );
        $this->assertEquals   ( $input['name'], $category->getAttribute('name') );
        $this->assertNotEquals( $input['nonexistant'], $category->getAttribute('nonexistant') );
        $this->assertEquals   ( $input['description'], $category->getAttribute('description') );
    }

    /**
     * Should attach uploaded image to category
     *
     */
    public function testShouldAttachImage()
    {
        $file = m::mock('UploadedFile');
        $file->shouldReceive('move')->once();

        $category = $this->aExistentCategory();

        $this->assertTrue( $category->attachUploadedImage( $file ) );
    }

    /**
     * Should get image URL
     *
     */
    public function testShouldGetImage()
    {
        $category = $this->aExistentCategory();

        $this->assertEquals(
            URL::to('assets/img/categories/'.$category->image),
            $category->imageUrl()
        );

        $category->image = '';

        $this->assertEquals(
            URL::to('assets/img/categories/default.png'),
            $category->imageUrl()
        );
    }

    /**
     * Should determine if an category exists
     */
    public function testShouldDetermineIfExists()
    {
        $category = $this->aExistentCategory();

        $this->assertTrue( Category::exists($category->name) );
        $this->assertFalse( Category::exists('Non-existant category') );
    }

    /**
     * Should activate category
     */
    public function testShouldActivate()
    {
        $category = $this->aExistentCategory();

        Category::activate($category->name);

        // Grab the category again since the activation is done
        // directly in the database
        $category = Category::first();

        $this->assertTrue( $category->active );
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
    private function aExistentCategory( $name = 'something' )
    {
        $category = new Category;

        $category->name = $name;
        $category->description = 'somedescription';
        $category->image = 'aimage.jpg';

        $category->save();

        return $category;
    }
    
}
