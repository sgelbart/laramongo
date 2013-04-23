<?php

use Laramongo\ImageGrabber\ImageGrabber;
use Laramongo\ImageGrabber\ImageImporter;
use Mockery as m;

class ImageGrabberTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        Config::set(
            'image_grabber.origin_url.product',
            app_path() . '/tests/assets/{lm}_{angle}_{size}.jpg'
        );

        Config::set(
            'image_grabber.origin_url.chave_entrada',
            app_path() . '/tests/assets/chave-entrada-{lm}.jpg'
        );

        Config::set('image_grabber.destination_url',
            'app/tests/assets/product/{lm}_{angle}_{size}.jpg'
        );

        $this->cleanCollection( 'products' );
    }

    public function testShouldImportImageToProduct()
    {
        $product = testProductProvider::instance('simple_valid_product');
        $product->_id = 100;
        $test = $this;

        $image_importer = m::mock(new ImageImporter);
        $image_importer
            ->shouldReceive('import')
            ->andReturnUsing(function($arg) use ($test)
            {
                return $test->curl_file($arg);
            });

        App::bind('ImageImporter', function() use($image_importer){ return $image_importer; });

        $product->grabImages();
    }

    public function testShouldImportImageToCategories()
    {

        Config::set('image_grabber.destination_url',
            'app/tests/assets/category/chave-entrada-{lm}.jpg'
        );

        $category = testCategoryProvider::instance('valid_leaf_category');
        $category->_id = '003501';
        $test = $this;

        $image_importer = m::mock(new ImageImporter);
        $image_importer
            ->shouldReceive('import')
            ->andReturnUsing(function($arg) use ($test)
            {
                return $test->curl_file($arg);
            });

        App::bind('ImageImporter', function() use($image_importer){ return $image_importer; });

        $category->grabImages();
    }


    public function curl_file($url)
    {
        if (file_exists($url)) {
            return file_get_contents($url);
        }

        return false;
    }
}
