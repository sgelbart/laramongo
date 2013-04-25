<?php 

use Laramongo\ExcelIo\ExcelImporter;
use Laramongo\ExcelIo\ExcelVintageImporter;

class ExcelVintageImporterTest extends TestCase {

    public $non_characteristic_keys;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
    }

    public function testShouldImportVintageExcelFile()
    {
        $products[] = testProductProvider::instance('simple_valid_product');
        $products[] = testProductProvider::instance('simple_deactivated_product');
        $products[] = testProductProvider::instance('product_with_details');
        $products[] = testProductProvider::instance('another_valid_product');
        $products[0]->details = ['alguma coisa'=>'algum valor'];

        $file = 'tests/assets/vintage_products.xlsx';

        $io = new ExcelVintageImporter;
        $result = $io->importFile($file);
        $this->assertTrue($result);

        foreach($products as $product) {
            // Should find the imported products
            $this->assertEquals(1, Product::where($product->_id)->count());
            $this->assertNotNull(Product::first($product->_id)->category());

            // Verify if getSuccess() returns the correct values
            $this->assertContains($product->_id, $io->getSuccess());
        }
    }
}
