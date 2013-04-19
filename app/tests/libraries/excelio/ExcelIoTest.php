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

    public function testShouldExportCategory()
    {
        // Save test data into database
        $category = testCategoryProvider::saved('valid_leaf_category');
        testProductProvider::saved('simple_valid_product');
        testProductProvider::saved('simple_deactivated_product');
        testProductProvider::saved('product_with_details');
        testProductProvider::saved('another_valid_product');
        $prod = Product::first();
        $prod->details = ['alguma coisa'=>'algum valor'];
        $prod->save();

        $output = '../public/uploads/'.clean_case($category->name).'.xlsx';

        $io = new ExcelIo;
        $result = $io->exportCategory($category, $output);

        $this->assertTrue($result);
        $this->assertFileExists(app_path().'/'.$output);
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
