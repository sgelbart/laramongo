<?php

use Laramongo\ImageGrabber\ImageGrabber;

class ImageGrabberTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
    }

    public function testShouldImportImageToProduct()
    {
        $product = testProductProvider::instance('simple_valid_product');
        $product->save();
    }

}
