<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class ImportPriceCsvContext extends BaseContext {

    public function __construct() { }

    /**
     * @Given /^I have an empty "([^"]*)" collection$/
     */
    public function iHaveAnEmptyCollection($collection)
    {
        $db = new Zizaco\Mongolid\MongoDbConnector;
        $db = $db->getConnection();

        $db->$collection->drop();
    }

    /**
     * @Given /^I have the following line in csv:$/
     */
    public function iHaveTheFollowingLineInCsv(TableNode $table)
    {
        $header = $table->getRows()[0];
        $values = $table->getRows()[1];

        $this->line = array_combine($header, $values);
    }

    /**
     * @When /^I process the line$/
     */
    public function iProcessTheLine()
    {
        throw new PendingException();
    }

}
