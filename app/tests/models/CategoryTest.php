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
     * Should attach uploaded image to category and
     * send image to S3 if it's enabled
     *
     */
    public function testShouldAttachImageAndSendToS3()
    {
        // Create category
        $category = f::create('Category');

        // The file that should be sent using the S3->sendFile() method
        $fileToSend = 'uploads/img/categories/'.$category->_id.'.jpg';

        // There must be a file to be deleted after upload, this will touch that file
        // if this file is not created than the HasImage->sendImageToNas() will fail
        $file = fopen(app_path().'/../public/'.$fileToSend,'w');
        fwrite($file, 'lorem ipsum');
        fclose($file);

        // Enable the S3 in configuration
        Config::set('s3.enabled',true);

        // Mock the S3 object and inject it in the IoC
        $mockS3 = m::mock('Laramongo\Nas\S3');
        $mockS3
            ->shouldReceive('sendFile')
            ->with($fileToSend)
            ->once()
            ->andReturn(true);

        app()['s3'] = $mockS3;

        // Run the default attachUploadedImage
        $file = m::mock('UploadedFile');
        $file->shouldReceive('move')->once();

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
            URL::to('uploads/img/categories/'.$category->image),
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
     * Assert if a category is visible or not. A visible category
     * consists in the following:
     * - hidden is not any sort of 'true'
     * - category has an _id
     */
    public function testCategoryVisibility()
    {
        // A non-saved category should not be visible
        $category = f::instance('Category');
        $this->assertFalse($category->isVisible());

        // A valid saved category should be visible
        $category = f::create('Category');
        $this->assertTrue($category->isVisible());

        // A hidden category should not be visible
        $category = f::create('Category', ['hidden'=>1]);
        $this->assertFalse($category->isVisible());
    }

    /**
     * Should deactivate a category
     */
    public function testShouldHideCategory()
    {
        $category = f::instance('Category');
        $category->hide();

        $this->assertTrue($category->hidden);
    }

    /**
     * Should activate a category
     */
    public function testShouldUnhideCategory()
    {
        $category = f::instance('Category', ['hidden'=>true]);
        $category->unhide();

        $this->assertNotEquals(true, $category->hidden);
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
