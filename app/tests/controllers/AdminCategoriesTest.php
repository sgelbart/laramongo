<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class AdminCategoriesTest extends ControllerTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'categories' );
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldIndex(){
        $this->requestAction('GET', 'Admin\CategoriesController@index');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Create action should always return 200
     *
     */
    public function testShouldCreate(){
        $this->requestAction('GET', 'Admin\CategoriesController@create');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Store action should redirect to index on success
     *
     */
    public function testShouldStore(){
        $input = f::attributesFor( 'Category' );

        $this->withInput($input)->requestAction('POST', 'Admin\CategoriesController@store');

        $this->assertRedirection(URL::action('Admin\CategoriesController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Store action should redirect to create when invalid
     *
     */
    public function testNotShouldStoreInvalid(){
        $input = f::attributesFor( 'Category' );
        $input['name'] = ''; // With blank name

        $this->withInput($input)->requestAction('POST', 'Admin\CategoriesController@store');

        $this->assertRedirection(URL::action('Admin\CategoriesController@create'));
        $this->assertSessionHas('error');
    }
}
