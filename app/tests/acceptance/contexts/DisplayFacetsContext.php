<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Mockery as m;

class DisplayFacetsContext extends BaseContext {

    public function __construct() { 
        parent::__construct();
        $this->cleanCollection( 'categories' );
    }

    /**
     * @Given /^a SearchEngine enabled for facets$/
     */
    public function aSearchengineEnabledForFacets()

    {
        // Enable search engine
        Config::set('search_engine.enabled', true);
        Config::set('search_engine.engine', 'mockedSearchEngine');

        $searchResult = [
            'Capacidade'=> [
                '_type'=>'terms',
                'total'=>3
            ],
            'Quantidade' => [
                '_type'=>'terms',
                'total'=>3
            ],
            'Coleção' => [
                '_type'=>'terms',
                'total'=>3
            ],
            'Cor' => [
                '_type'=>'terms',
                'total'=>3
            ]
        ];

        // Prepare mocked searchEngine
        $mockedSearchEng = m::mock('Es')
            ->shouldReceive('connect')->getMock()
            ->shouldReceive('mapCategory')->getMock()
            ->shouldReceive('indexObject')->getMock();

        $mockedSearchEng->shouldReceive('facetSearch')
            ->once();
        $mockedSearchEng->shouldReceive('getFacetResult')
            ->once()
            ->andReturn($searchResult);

        App::bind('mockedSearchEngine', function() use ($mockedSearchEng){
            return $mockedSearchEng; 
        });
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

        $this->bodyText = $this->testCase()->getBodyText();

        $this->testCase()->assertRequestOk();
    }

    /**
     * @Then /^I should see the facets:$/
     */
    public function iShouldSeeTheFacets(TableNode $facets)
    {
        foreach ($facets->getRows() as $facet) {
            $this->testCase()->assertBodyHasText($facet);
        }

        m::close();
    }
}
