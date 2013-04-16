<?php

use Mockery as m;

class ImageContentTest extends TestCase
{
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
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Should render the content
     */
    public function testShouldRenderImage()
    {
        $content = testContentProvider::saved('valid_image');

        // Check for the most relevant embeded code
        $this->assertContains( '<img',          $content->render() );
        $this->assertContains( 'src',           $content->render() );
        $this->assertContains( $content->image, $content->render() );
        $this->assertContains( $content->name,  $content->render() );
    }

    /**
     * Should tag in the image a product that is related to the content
     *
     */
    public function testShouldTagProductToImage()
    {
        // Creates an ImageContent and a Product
        $content = testContentProvider::saved('valid_image');
        $product = testProductProvider::saved('simple_valid_product');

        // Attach the product to the ImageContent
        $content->attachToProducts( $product );
        $content->save();

        // tagProduct in Content
        $this->assertTrue($content->tagProduct($product, 50,60));

        // Check if the product was tagged correctly
        $real = $content->tagged[0];
        $should_be = ['_id'=>$product->_id, 'x'=>50, 'y'=>60];
        $this->assertEquals($should_be, $real);
    }

    /**
     * Should NOT tag in the image a product that is NOT related to the content
     *
     */
    public function testShouldNotTagUnrelatedProductToImage()
    {
        // Creates an ImageContent and a Product
        $content = testContentProvider::saved('valid_image');
        $product = testProductProvider::saved('simple_valid_product');

        // Don't attach the product to the image

        // tagProduct in Content
        $this->assertFalse($content->tagProduct($product, 50,60));

        // Check if the product was not tagged
        $this->assertEquals(0, count($content->tagged));
    }

    /**
     * Should attach uploaded image to content
     *
     */
    public function testShouldAttachImage()
    {
        $file = m::mock('UploadedFile');
        $file->shouldReceive('move')->once();

        $content = testContentProvider::saved('valid_image');

        $this->assertTrue( $content->attachUploadedImage( $file ) );
    }

    /**
     * Should attach uploaded image to content and
     * send image to S3 if it's enabled
     *
     */
    public function testShouldAttachImageAndSendToS3()
    {
        // Create content
        $content = testContentProvider::saved('valid_image');

        // The file that should be sent using the S3->sendFile() method
        $fileToSend = 'uploads/img/contents/'.$content->_id.'.jpg';

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

        $this->assertTrue( $content->attachUploadedImage( $file ) );
    }

    /**
     * Should get image URL
     *
     */
    public function testShouldGetImage()
    {
        $content = testContentProvider::saved('valid_image');
        $content->image = 'default.jpg';
        $content->save();

        $this->assertEquals(
            URL::to('uploads/img/contents/'.$content->image),
            $content->imageUrl()
        );

        $content->image = '';

        $this->assertEquals(
            URL::to('assets/img/contents/default.png'),
            $content->imageUrl()
        );
    }
}
