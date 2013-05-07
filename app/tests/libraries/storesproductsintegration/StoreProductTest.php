<?php

class StoreProductTest extends Zizaco\TestCases\TestCase
{
    public function testShouldCalcRegionPrice()
    {
        $storeProduct = testStoreProductProvider::instance('simple_valid_product_price');
        
        $result = $storeProduct->calculateRegionPrice('grande_sao_paulo');
        $this->assertEquals( ['to_price'=>7.0, 'from_price'=>11.98], $result );
    }

    public function testSavePriceInRealProduct()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $storeProduct = testStoreProductProvider::instance('simple_valid_product_price');
        
        $this->assertNull( $product->price );
        $this->assertTrue( $storeProduct->save() );

        $product = Product::first($product->_id);

        $this->assertNotNull( $product->price );
    }
}
