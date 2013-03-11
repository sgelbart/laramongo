<?php

use Zizaco\CsvToMongo\ImageUnzipper;

class ImageUnzipperTest extends TestCase {
    
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

        $this->img_dir = app_path().'/../public/assets/img/products/';

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
