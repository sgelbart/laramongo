<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ProductImportContext extends BaseContext {

    public function __construct()
    {
        $this->testCase()->cleanCollection( 'categories' );
        $this->testCase()->cleanCollection( 'products' );
    }

    /**
     * @Then /^I should see the import report of "([^"]*)"$/
     */
    public function iShouldSeeTheImportReportOf($category)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have the category "([^"]*)"$/
     */
    public function iHaveTheCategory($category_name)
    {
        $this->category = testCategoryProvider::saved($category_name);
    }

    /**
     * @Given /^I have the product "([^"]*)"$/
     */
    public function iHaveTheProduct($product_name)
    {

        $this->$product_name = testProductProvider::saved($product_name);
    }

    /**
     * @When /^I import the "([^"]*)"$/
     */
    public function iImportThe($file)
    {
        $path = 'tests/assets/'.$file;

        $this->lastImport = new Import;
        $this->lastImport->filename = $path;
        $this->lastImport->save();
        $this->lastImport = Import::first($this->lastImport->_id);
    }

    /**
     * @Then /^I should get the four new producs into database$/
     */
    public function iShouldGetTheFourNewProducsIntoDatabase()
    {
        // Mimics the values from the "new_products.xlsx"
        $products[0] = testProductProvider::instance('simple_valid_product');
        $products[0]->details = ['alguma coisa'=>'Algum valor'];
        $products[] = testProductProvider::instance('simple_deactivated_product');
        $products[] = testProductProvider::instance('product_with_details');
        $products[] = testProductProvider::instance('another_valid_product');

        foreach($products as $product) {

            // Should find the imported products
            $this->testCase()->assertEquals(1, Product::where($product->_id)->count());
            $this->testCase()->assertEquals((string)$this->category->_id, Product::first($product->_id)->category);
            $this->testCase()->assertEquals((array)$product->details, (array)Product::first($product->_id)->details);
        }

        // Check if there are 4 products into the database
        $this->testCase()->assertEquals(4, Product::all()->count());
    }

    /**
     * @Then /^I should see the import report with "([^"]*)" errors$/
     */
    public function iShouldSeeTheImportReportWithErrors($amount)
    {
        $this->testCase()->requestAction(
            'GET', 'Admin\ProductsController@importResult',
            ['id'=>$this->lastImport->_id]
        );

        $this->testCase()->assertRequestOk();
        $this->testCase()->assertBodyHasText('Produtos com erro '.$amount);
    }
}
