<?php

use Mockery as m;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

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

    public function testShouldNotSaveDuplicated()
    {
        $category = new Category;
        $category->name = 'something';
        $category->save();

        $category = new Category;
        $category->name = 'something';

        // Should return false, since there is already a category with that name
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
     * Should attach uploaded image to category
     *
     */
    public function testShouldAttachImage()
    {
        $file = m::mock('UploadedFile');
        $file->shouldReceive('move')->once();

        $category = f::create('Category');

        $this->assertTrue( $category->attachUploadedImage( $file ) );
    }

    /**
     * Should get image URL
     *
     */
    public function testShouldGetImage()
    {
        $category = f::create('Category');
        $category->image = 'default.jpg';
        $category->save();

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
     * Should build ancestors tree
     */
    public function testShouldBuildAncestorsWhenSave()
    {
        $grandParent = f::create('Category');
        $parentA = f::create('Category');
        $parentB = f::create('Category');
        $child = f::create('Category', ['kind'=>'']);

        $parentA->attachToParents($grandParent);
        $parentA->save();

        $child->attachToParents($parentA);
        $child->attachToParents($parentB);
        $child->save();

        $this->assertEquals(
            $child->parents()->toArray(false)[0],
            $child->ancestors()[0]
        );

        $this->assertEquals(
            $child->parents()->toArray(false)[1],
            $child->ancestors()[1]
        );

        $this->assertEquals(
            $child->parents()->toArray(false)[0]->ancestors(),
            $child->ancestors()[0]->ancestors()
        );
    }

    /**
     * Should validate all products within this category
     */
    public function testValidateProducts()
    {
        $category = f::create('Category', ['kind'=>'leaf']);

        $this->assertTrue($category->validateProducts());
    }
}
