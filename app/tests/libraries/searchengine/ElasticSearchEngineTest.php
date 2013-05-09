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
        Config::set('search_engine.application_name', 'dev');
        m::close();
    }

    public function testShouldIndexObject()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $attributes = $product->getAttributes();

        unset($attributes['_id']);

        $this->mockedEs
            ->shouldReceive('index')
            ->with($attributes, $product->_id)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($product);
    }

    public function testShouldSearchObject()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        // cleaning cached element
        $elements = array('products', 'contents', 'categories');

        $engine = new ElasticSearchEngine;
        $engine->connect();

        foreach ($elements as $element) {
            $engine->prepareIndexationPath($element);
            $engine->es->delete();
        }

        // creating a product
        $product = testProductProvider::saved('simple_valid_product');
        $engine->searchObject();

        $result = $engine->getResultBy('products');

        Config::set('search_engine.application_name', 'dev');

        $this->assertInstanceOf('Product', $result);
    }
}
