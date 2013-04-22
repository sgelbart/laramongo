<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class DeactvateProductTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
    }

    public function testShouldToggleProductsInIndex()
    {
        $product = f::create('Product');

        $this->browser
            ->open(URL::action('Admin\ProductsController@index'))
            ->click(l::css('#row-'.$product->_id.' a[data-ajax=true]'));

        sleep(1); // Wait for ajax =(

        $product = Product::first($product->_id);
        $this->assertTrue($product->deactivated);

        $this->browser
            ->click(l::css('#row-'.$product->_id.' a[data-ajax=true]'));

        sleep(1); // Wait for ajax =(

        $product = Product::first($product->_id);
        $this->assertNotEquals(true, $product->deactivated);
    }
}
