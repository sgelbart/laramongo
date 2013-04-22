<?php 

use Laramongo\ExcelIo\ExcelIo;

class ExcelIoTest extends TestCase {

    public $non_characteristic_keys;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );

        $this->non_characteristic_keys = [
            '_id'=>true,'name'=>true,'description'=>true,'small_description'=>true,'category'=>true,'products'=>true
        ];
    }

    public function testShouldExportAndImportCategory()
    {
        // Save test data into database
        $category = testCategoryProvider::saved('valid_leaf_category');
        $products[] = testProductProvider::saved('simple_valid_product');
        $products[] = testProductProvider::saved('simple_deactivated_product');
        $products[] = testProductProvider::saved('product_with_details');
        $products[] = testProductProvider::saved('another_valid_product');
        $prod = Product::first();
        $prod->details = ['alguma coisa'=>'algum valor'];
        $prod->save();

        $output = '../public/uploads/'.clean_case($category->name).'.xlsx';

        $io = new ExcelIo;
        $result = $io->exportCategory($category, $output);

        // Check if the File has been exported
        $this->assertTrue($result);
        $this->assertFileExists(app_path().'/'.$output);

        // Clean prducts
        foreach( Product::all() as $product ) { $product->delete(); }

        $result = $io->importFile($output);
        $this->assertTrue($result);

        foreach($products as $product) {
            // Should find the imported products
            $this->assertEquals(1, Product::where($product->_id)->count());
            $this->assertEquals((string)$category->_id, Product::first($product->_id)->category);
        }
    }

    public function testShouldBuildSchema()
    {
        // Save test data into database
        $category = testCategoryProvider::saved('valid_leaf_category');
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('simple_deactivated_product');
        testProductProvider::saved('another_valid_product');
        $prod = Product::first();
        $prod->details = ['alguma coisa'=>'algum valor'];
        $prod->save();

        $io = new ExcelIo;

        $result = $io->buildSchema($category); // Without products cursor
        $should_be = array_merge(
            $this->non_characteristic_keys,
            ['capacidade'=>true, 'cor'=>true]
        ); // clean_case() is applied

        $this->assertEquals($should_be, $result);

        $result = $io->buildSchema($category, Product::all()); // Width products cursor
        $should_be = array_merge($should_be, ['alguma coisa'=>false]); // clean_case() is applied

        $this->assertEquals($should_be, $result);
    }

}
