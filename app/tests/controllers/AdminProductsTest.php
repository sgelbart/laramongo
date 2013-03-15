<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class AdminProductsTest extends ControllerTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldIndex(){
        $this->requestAction('GET', 'Admin\ProductsController@index');
        $this->assertRequestOk();
    }

    /**
     * Create action should always return 200
     *
     */
    public function testShouldCreate(){
        $this->requestAction('GET', 'Admin\ProductsController@create');
        $this->assertRequestOk();
    }

    /**
     * Store action should redirect to index on success
     *
     */
    public function testShouldStore(){
        $input = f::attributesFor( 'Product' );

        $this->withInput($input)->requestAction('POST', 'Admin\ProductsController@store');

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Store action shoud redirect to create when input
     * is not valid
     */
    public function testShouldNotStoreInvalid(){
        $input = f::attributesFor( 'Product' );
        $input['name'] = ''; // With blank name

        $this->withInput($input)->requestAction('POST', 'Admin\ProductsController@store');

        $this->assertRedirection(URL::action('Admin\ProductsController@create'));
        $this->assertSessionHas('error');
    }

    /**
     * Show action should redirect to edit action of the same product id
     *
     */
    public function testShouldShow(){
        $this->requestAction('GET', 'Admin\ProductsController@show', ['id' => 0]);
        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id' => 0]));
    }

    /**
     * The edit action should return 200 when the id exists in database
     *
     */
    public function testShouldEditExistent(){
        $product = f::create( 'Product' );

        /* Some laravel bug is happening here */
        $this->requestUrl('GET', URL::action('Admin\ProductsController@index', ['id'=>$product->_id]));
        $this->assertRequestOk();
    }

    /**
     * Edit action should redirect to index if product doesn't exists
     *
     */
    public function testShouldNotEditNull(){
        $this->requestAction('GET', 'Admin\ProductsController@edit', ['id'=>'123']);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','não encontrad');
    }

    /**
     * Update action should update existent product and redirect to index
     *
     */
    public function testShouldUpdateExistent(){
        $product = f::create( 'Product' );

        $this->withInput( $product->getAttributes() )
            ->requestAction('PUT', 'Admin\ProductsController@update', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Update action should redirect to edit form of the same product if update
     * input is invalid
     *
     */
    public function testShouldNotUpdateWithInvalidInput(){
        $product = f::create( 'Product' );
        $product->name = '';

        $this->withInput( $product->getAttributes() )
            ->requestAction('PUT', 'Admin\ProductsController@update', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id'=>$product->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Destroy action should redirect to index
     *
     */
    public function testShouldDestroy(){
        $product = f::create( 'Product' );

        $this->requestAction('DELETE', 'Admin\ProductsController@destroy', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucess');
    }

    /**
     * Import action should always return 200 
     *
     */
    public function testShowImportDialog(){
        $this->requestAction('GET', 'Admin\ProductsController@import');
        $this->assertRequestOk();
    }
}
