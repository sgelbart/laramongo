<?php

class SearchTest extends ControllerTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'contents' );
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldSearchForProducts(){
        $this->withInput(['search'=>'L','aditional_id'=>1])
            ->requestAction('GET', 'SearchController@products', ['view'=>'relate_products'])
            ->assertRequestOk();
    }
}
