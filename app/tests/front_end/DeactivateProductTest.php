<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class DeactvateProductTest extends Zizaco\TestCases\IntegrationTestCase
{
    use TestHelper;

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
        $product = testProductProvider::saved('simple_valid_product');

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
