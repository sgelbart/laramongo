<?php

use Mockery as m;

class SearchableTraitTest extends Zizaco\TestCases\TestCase
{
    protected $mockedSearchEng;

    public function setUp()
    {
        parent::setUp();

        // Enable search engine
        Config::set('search_engine.enabled', true);
        Config::set('search_engine.engine', 'mockedSearchEngine');

        // Prepare mocked searchEngine
        $this->mockedSearchEng = m::mock('Es');
    }

    public function tearDown()
    {
        // Disable search engine and close mockery
        Config::set('search_engine.enabled', false);
        m::close();
    }

    public function testShouldSearchEngineIndex()
    {
        $model = new _modelStub;

        $this->mockedSearchEng
            ->shouldReceive('indexObject')
            ->with($model)
            ->once();

        $mock = $this->mockedSearchEng;

        App::bind('mockedSearchEngine', function() use ($mock){
            return $mock; 
        });

        $model->searchEngineIndex();
    }
}

class _modelStub{

    protected $collection = 'stubs';

    use Traits\Searchable;
}
