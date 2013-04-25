<?php

use Laramongo\ImageGrabber\ImageGrabber;
use Laramongo\ImageGrabber\RemoteImporter;
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

        Config::set('image_grabber.destination_url.chave_entrada',
            'app/tests/assets/category/chave_entrada_{lm}_{name_chave_entrada}.jpg'
        );

        Config::set('image_grabber.destination_url.product',
            'app/tests/assets/product/{name_product}_{lm}_{angle}_{size}.jpg'
        );

        $this->cleanCollection( 'products' );
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }

    public function testShouldImportImageToProduct()
    {
        // Make sure the validator is calling validate method
        $validatorMock = m::mock('Laramongo\ImageGrabber\Validator');
        $validatorMock->shouldReceive('validate')->atLeast(1)->andReturn(true);
        \App::instance('ImageGrabber\Validator', $validatorMock);

        $product = testProductProvider::instance('simple_valid_product');
        $product->_id = 100;
        $test = $this;

        $image_importer = m::mock(new RemoteImporter);
        $image_importer
            ->shouldReceive('import')
            ->andReturnUsing(function($arg) use ($test)
            {
                return $test->curl_file($arg);
            });

        App::bind('RemoteImporter', function() use($image_importer){ return $image_importer; });

        $urlImages = $product->grabImages();

        // if File exists
        $this->assertTrue(
            file_exists(
                "app/tests/assets/product/" .
                ruby_case($product->name) .
                "_100_1_300.jpg"
            )
        );

        // if the imageUrls is valid
        $this->assertEquals(
            $urlImages,
            array(
                ruby_case($product->name) .
                "_100_1_300.jpg"
            )
        );

        $this->assertEquals(
            $product->image,
            array(
                ruby_case($product->name) .
                "_100_1_300.jpg"
            )
        );
    }

    // just to mock the request image
    public function curl_file($url)
    {
        if (file_exists($url)) {
            return file_get_contents($url);
        }

        return false;
    }
}
