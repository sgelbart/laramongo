<?php 

use Laramongo\ExcelIo\ExcelExporter;
use Laramongo\ExcelIo\ExcelImporter;

class ExcelIoTest extends Zizaco\TestCases\TestCase {

    public $non_characteristic_keys;

    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );

        $this->non_characteristic_keys = [
            '_id'=>true,'name'=>true,'description'=>true,'small_description'=>true,'category'=>true,'products'=>true, 'conjugated'=>true
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

        $io = new ExcelExporter;
        $result = $io->exportCategory($category, $output);

        // Check if the File has been exported
        $this->assertTrue($result);
        $this->assertFileExists(app_path().'/'.$output);

        // Clean prducts
        foreach( Product::all() as $product ) { $product->delete(); }

        $io = new ExcelImporter;
        $result = $io->importFile($output);
        $this->assertTrue($result);

        foreach($products as $product) {
            // Should find the imported products
            $this->assertEquals(1, Product::where($product->_id)->count());
            $this->assertEquals((string)$category->_id, Product::first($product->_id)->category);

            // Verify if getSuccess() returns the correct values
            $this->assertContains($product->_id, $io->getSuccess());
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

        $io = new ExcelExporter;

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

    public function testShouldReportErrorOnImport()
    {
        $category = testCategoryProvider::saved('valid_lixeiras_category');

        $path = 'tests/assets/lixeirasAlgumasErradas.xlsx';

        $io = new ExcelImporter;
        $io->importFile($path);

        $this->assertEquals(6, count($io->getSuccess()));
        $this->assertEquals(1, count($io->getErrors()));

        // Each error found should have an 'error' key containing
        // the reason
        $errors = $io->getErrors();
        $this->assertNotNull($errors[0]['error']);
    }
}
