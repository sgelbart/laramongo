<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class DisplayFacetsContext extends BaseContext {

    public function __construct() { 
        parent::__construct();
        $this->cleanCollection( 'categories' );
    }

    /**
     * @Given /^I visit the "([^"]*)" category page$/
     */
    public function iVisitTheCategoryPage($category_name)
    {
        $category_id = testCategoryProvider::instance($category_name)->_id;

        $this->testCase()->requestAction(
            'GET', 'CategoriesController@show',
            ['id'=> (string)$category_id]
        );

        $this->testCase()->assertRequestOk();
    }

}
