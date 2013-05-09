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
        $this->requestAction('GET', 'SearchEngineController@search', ['query' => 'coisa']);
        $this->assertRequestOk();
    }
}
