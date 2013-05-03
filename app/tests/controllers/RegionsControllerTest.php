<?php

class RegionsControllerTest extends ControllerTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        Session::forget('region');
    }

    public function testShouldRedictToRegionCreateIfNotSession()
    {
        $this->requestAction('GET', 'HomeController@index');
        $this->assertStatusCode(302);
        $this->assertRedirection(URL::to( 'regions/create' ));
    }

    public function testShouldRedirectToTheOriginUrl()
    {
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $this->requestAction(
            'GET', 'CategoriesController@show', [ 'id' => $category->_id ]
        );

        $this->assertStatusCode(302);

        $this->assertRedirection(URL::to('regions/create'));

        $this->requestAction(
            'POST', 'RegionsController@store', [ 'region' => 'sao_paulo' ]
        );

        $this->assertRedirection(
            URL::action('CategoriesController@show', ['id' => $category->_id] )
        );
    }
}
