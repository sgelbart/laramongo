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

    public function __construct() {
        parent::__construct();
    }

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

        $attributes = $this->simple_valid_product->getAttributes();
        unset($attributes['_id']);

        $this->mockedEs
            ->shouldReceive('index')
            ->with($attributes, $this->simple_valid_product->_id)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($this->simple_valid_product);
    }

    /**
     * @Then /^should have retrieved a index of product$/
     */
    public function shouldHaveRetrievedAIndexOfProduct()
    {
        Config::set('search_engine.enabled', true);

        // Prepare mocked searchEngine
        $this->mockedEs = m::mock('Es');
        $this->mockedEs->shouldReceive('setIndex');
        $this->mockedEs->shouldReceive('setType');


        $attributes = $this->simple_valid_product->getAttributes();
        unset($attributes['_id']);

        $this->mockedEs
            ->shouldReceive('index')
            ->with($attributes, $this->simple_valid_product->_id)
            ->once();

        $this->mockedEs
            ->shouldReceive('search')
            ->once();

        $this->mockedEs
            ->shouldReceive('getResultBy')
            ->with('products');

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($this->simple_valid_product);
        $searchEngine->searchObject();
    }
}
