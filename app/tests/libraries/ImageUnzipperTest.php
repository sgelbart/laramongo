<?php

use Zizaco\CsvToMongo\ImageUnzipper;
use Mockery as m;

class ImageUnzipperTest extends Zizaco\TestCases\TestCase {
    /**
     * Directory where the product images will be extracted
     */
    public $img_dir;

    /**
     * Clear existing files
     */
    public function setUp()
    {
        parent::setUp();

        $this->img_dir = app_path().'/../public/uploads/img/products/';

        $this->clearFiles();
    }

    /**
     * Clear existing files after tests
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->clearFiles();
    }

    /**
     * Should extract each of the files
     */
    public function testShouldUnzipImages()
    {
        $file = __DIR__.'/image_sample.zip';

        $imgu = new ImageUnzipper( $file );
        $imgu->extract();

        foreach( ['red','green','yellow'] as $color )
        {
            $this->assertTrue( file_exists($this->img_dir.$color.'.jpg') );
        }
    }

    /**
     * Should send images to S3 if enabled
     */
    public function testShouldSendImagesToS3()
    {
        // Enable the S3 in configuration
        Config::set('s3.enabled',true);

        // Mock the S3 object and inject it in the IoC
        $mockS3 = m::mock('Laramongo\Nas\S3');
        $mockS3
            ->shouldReceive('sendFile')
            ->with('uploads/img/products/green.jpg')
            ->once()
            ->andReturn(true);

        $mockS3
            ->shouldReceive('sendFile')
            ->with('uploads/img/products/red.jpg')
            ->once()
            ->andReturn(true);

        $mockS3
            ->shouldReceive('sendFile')
            ->with('uploads/img/products/yellow.jpg')
            ->once()
            ->andReturn(true);

        app()['s3'] = $mockS3;

        // Unzip the file normally
        $file = __DIR__.'/image_sample.zip';

        $imgu = new ImageUnzipper( $file );
        $imgu->extract();

        // At least assertFALSE, because the files should have been deleted
        // right after being sent to S3 ;)
        foreach( ['red','green','yellow'] as $color )
        {
            $this->assertFalse( file_exists($this->img_dir.$color.'.jpg') );
        }
    }

    /**
     * Clear the extracted files
     */
    private function clearFiles()
    {
        foreach( ['red','green','yellow'] as $color )
        {
            if( file_exists($this->img_dir.$color.'.jpg') )
            unlink($this->img_dir.$color.'.jpg');
        }
    }
}
