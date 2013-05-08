<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Laramongo\SearchEngine\ElasticSearchEngine;

use Mockery as m;

class IndexResourcesContext extends BaseContext {

     /**
     * @Then /^should have indexed product at Search Engine$/
     */
    public function shouldHaveIndexedProductAtSearchEngine()
    {
        Config::set('search_engine.enabled', true);

        // Prepare mocked searchEngine
        $this->mockedEs = m::mock('Es');
        $this->mockedEs->shouldReceive('setIndex');
        $this->mockedEs->shouldReceive('setType');

        $this->mockedEs
            ->shouldReceive('index')
            ->with($this->simple_valid_product->getAttributes(), $this->simple_valid_product->_id)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($this->simple_valid_product);
    }
}
