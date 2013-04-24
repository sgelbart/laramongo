<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class FeatureContext extends BaseContext {

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

        $io = new Laramongo\ExcelIo\ExcelIo;
        $io->importFile($path);
    }

    /**
     * @Then /^I should get the four new producs into database$/
     */
    public function iShouldGetTheFourNewProducsIntoDatabase()
    {
        // Mimics the values from the "new_products.xlsx"
        $products[0] = testProductProvider::instance('simple_valid_product');
        $products[0]->details = ['alguma coisa'=>'algum valor'];
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
     * @Then /^I should get no products into database$/
     */
    public function iShouldGetNoProductsIntoDatabase()
    {
        // Check if there is no products in the database
        $this->testCase()->assertEquals(0, Product::all()->count());
    }
}
