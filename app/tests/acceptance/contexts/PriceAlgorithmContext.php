<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Laramongo\StoresProductsIntegration\PriceCalculator;

class PriceAlgorithmContext extends BaseContext {
    /**
     * The price table for a product
     * @var array
     */
    private $priceTable = array();
    private $priceCalculator = '';

    public function __construct() {
        parent::__construct();
    }

    /**
     * @Given /^the stores which has the following prices:$/
     */
    public function theStoresWhichHasTheFollowingPrices(TableNode $table)
    {
        $this->priceTable = $table->getHash();
    }

    /**
     * @When /^run the calculation$/
     */
    public function runTheCalculation()
    {
        $this->priceCalculator = new PriceCalculator();
        $this->priceCalculator->addPriceTable($this->priceTable);
        $this->priceCalculator->runCalculation();
    }

    /**
     * @Then /^should get for the region:$/
     */
    public function shouldGetForTheRegion(TableNode $table)
    {
        $result = $this->priceCalculator->getResultByRegion();

        $this->testCase()->assertEquals($table->getHash()[0], $result);
    }
}
