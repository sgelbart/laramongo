<?php
use Mockery as m;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class CategoryTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

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
        $category = testCategoryProvider::instance('valid_leaf_category');

        // Should return true, since it's a valid category
        $this->assertTrue( $category->isValid() );

        $category->name = '';

        // Should return false, since the name is empty
        $this->assertFalse( $category->isValid() );
    }

    public function testShouldSaveDuplicated()
    {
        $category = testCategoryProvider::instance('valid_leaf_category');
        $category->name = 'The Name Of Category';
        $category->save();

        $category = testCategoryProvider::instance('another_valid_leaf_category');
        $category->name = 'The Name Of Category';

        // Should return true, since now the name of the categories can be the same
        $this->assertTrue( $category->isValid() );
    }

    /**
     * Should save valid category
     *
     */
    public function testShouldSaveValidCategory()
    {
        $category = testCategoryProvider::instance('valid_leaf_category');

        // Should return true, since it's a valid category
        $this->assertTrue( $category->save() );
    }

    /**
     * Should NOT save invalid category
     *
     */
    public function testShouldNotSaveInvalidCategory()
    {
        $category = testCategoryProvider::instance('invalid_leaf_category');

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

        $category = testCategoryProvider::saved('valid_leaf_category');

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
        $category = testCategoryProvider::saved('valid_leaf_category');

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
        $category = testCategoryProvider::saved('valid_leaf_category');
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
        $grandParent = testCategoryProvider::instance('valid_department');
        $parentA = testCategoryProvider::instance('another_valid_parent_category');
        $parentB = testCategoryProvider::instance('valid_parent_category');
        $child = testCategoryProvider::instance('another_valid_leaf_category');

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
        $category = testCategoryProvider::instance('valid_leaf_category');
        unset($category->_id);
        $this->assertFalse($category->isVisible());

        // A valid saved category should be visible
        $category = testCategoryProvider::saved('valid_leaf_category');
        $this->assertTrue($category->isVisible());

        // A hidden category should not be visible
        $category = testCategoryProvider::saved('hidden_leaf_category');
        $this->assertFalse($category->isVisible());
    }

    /**
     * Should deactivate a category
     */
    public function testShouldHideCategory()
    {
        $category = testCategoryProvider::saved('valid_leaf_category');
        $category->hide();

        $this->assertTrue($category->hidden);
    }

    /**
     * Should activate a category
     */
    public function testShouldUnhideCategory()
    {
        $category = testCategoryProvider::saved('hidden_leaf_category');
        $category->unhide();

        $this->assertNotEquals(true, $category->hidden);
    }

    /**
     * Should validate all products within this category
     */
    public function testValidateProducts()
    {
        $category = testCategoryProvider::saved('valid_leaf_category');

        $this->assertTrue($category->validateProducts());
    }

    public function testShouldRenderPopover()
    {
        $category = testCategoryProvider::instance('valid_leaf_category');

        $this->assertContains('<div',$category->renderPopover());
        $this->assertContains('<span',$category->renderPopover());
        $this->assertContains('bacon',$category->renderPopover('bacon'));
    }

    public function testShouldIndexCategoryOnSave()
    {
        // Enable search engine
        Config::set('search_engine.enabled', true);
        Config::set('search_engine.engine', 'mockedSearchEngine');

        // Prepare mocked searchEngine
        $mockedSearchEng = m::mock('Es');
        $mockedSearchEng->shouldReceive('indexObject')->atLeast(1);

        App::bind('mockedSearchEngine', function() use ($mockedSearchEng){
            return $mockedSearchEng; 
        });

        $category = testCategoryProvider::instance('valid_leaf_category');
        $category->save(); // This should call the SearchEngine->indexObject

        Config::set('search_engine.enabled', false);
    }

    public function testShoultMapFacetsOnSave()
    {
        // Enable search engine
        Config::set('search_engine.enabled', true);
        Config::set('search_engine.engine', 'mockedSearchEngine');

        // Prepare mocked searchEngine
        $mockedSearchEng = m::mock('Es');
        $mockedSearchEng->shouldReceive('indexObject')->once(1);

        App::bind('mockedSearchEngine', function() use ($mockedSearchEng){
            return $mockedSearchEng; 
        });

        $category = testCategoryProvider::instance('leaf_with_facets');
        $category->save();

        Config::set('search_engine.enabled', false);
    }

    public function testShouldGetFacets()
    {
        $category = testCategoryProvider::instance('leaf_with_facets');

        $result = $category->getFacets();

        foreach ($category->characteristics() as $charac) {
            $this->assertContains($charac->name, array_keys($result));
        }
    }

    public function testShouldGetHistogramFacet()
    {
        $category = testCategoryProvider::instance('leaf_with_facets');

        $result = $category->getFacets();

        foreach ($category->characteristics() as $charac) {

            if( $charac->type == 'int' || $charac->type == 'float' )
            {
                $this->assertContains( 'histogram' ,array_keys($result[$charac->name]));    
            }    
        }
    }
}
