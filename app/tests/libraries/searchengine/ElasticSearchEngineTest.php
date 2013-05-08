<?php

use Mockery as m;
use Laramongo\SearchEngine\ElasticSearchEngine;

class ElasticSearchEngineTest extends Zizaco\TestCases\TestCase {

    protected $mockedEs;

    public function setUp()
    {
        parent::setUp();

        // Enable search engine
        Config::set('search_engine.enabled', true);

        // Prepare mocked searchEngine
        $this->mockedEs = m::mock('Es');
        $this->mockedEs->shouldReceive('setIndex');
        $this->mockedEs->shouldReceive('setType');
    }

    public function tearDown()
    {
        // Disable search engine and close mockery
        Config::set('search_engine.enabled', false);
        m::close();
    }

    public function testShouldIndexObject()
    {
        $product = testProductProvider::saved('simple_valid_product');

        // Important, the array sent to the elasticsearch should not contain the _id within the
        // first parameter
        $should_send = $product->getAttributes();
        unset($should_send['_id']);

        $this->mockedEs
            ->shouldReceive('index')
            ->with($should_send, $product->_id)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($product);
    }
}
