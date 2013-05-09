<?php

class SearchEngineControllerTest extends ControllerTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
    }

    public function testShouldCanSearchSomething()
    {
        $this->requestUrl('GET', 'search?query=coisa');
        $this->assertRequestOk();
    }

    public function testShouldRedirectToHomeIfHasNotStringToQuery()
    {
        $this->requestAction('GET', 'SearchEngineController@search');
        $this->assertRedirection(URL::action('HomeController@index'));
    }
}
