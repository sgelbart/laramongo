<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class PrioritizeFacetsContext extends BaseContext {

    public function __construct() { 
        parent::__construct();
        $this->cleanCollection( 'categories' );
    }

    /**
     * @When /^I priorize the characteristics of "([^"]*)" like:$/
     */
    public function iPriorizeTheCharacteristicsOfLike($category_name, TableNode $order)
    {
        $category_id = (string)testCategoryProvider::instance($category_name)->_id;

        foreach ($order->getRows() as $row) {

            $this->testCase()
                ->withInput([ 'priority' => $row[1] ])
                ->requestAction(
                    'PUT', 'Admin\CategoriesController@update_characteristic',
                    ['id'=> $category_id, 'charac_name'=> $row[0]]
                );

            $this->testCase()->assertRedirection();
        }
    }

    /**
     * @Then /^I should see the facets in the following order:$/
     */
    public function iShouldSeeTheFacetsInTheFollowingOrder(TableNode $facets)
    {
        $bodyText = $this->bodyText;

        foreach ($facets->getRows() as $facet) {
            echo $facet[0].' | '.$facet[1]." \n";
            echo strpos($bodyText, $facet[0]);
        }
    }
}
