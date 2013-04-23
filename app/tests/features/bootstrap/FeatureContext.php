<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

    require_once 'PHPUnit/Autoload.php';
    require_once 'PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BaseContext {

    /**
     * @Given /^I have the category "([^"]*)"$/
     */
    public function iHaveTheCategory($category_name)
    {
        $this->testCase()->cleanCollection( 'categories' );
        $this->testCase()->cleanCollection( 'products' );

        $this->category = testCategoryProvider::saved($category_name);
    }

    /**
     * @When /^I am importing the "([^"]*)"$/
     */
    public function iAmImportingThe($file)
    {
        $path = 'tests/assets/'.$file;

        $io = new Laramongo\ExcelIo\ExcelIo;
        $io->importFile($path);
    }

    /**
     * @Then /^I should get the new producs into database$/
     */
    public function iShouldGetTheNewProducsIntoDatabase()
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
    }
}
