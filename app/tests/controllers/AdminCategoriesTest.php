<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class AdminCategoriesTest extends Zizaco\TestCases\ControllerTestCase
{
    use TestHelper;

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
        $this->assertRequestOk();
    }

    /**
     * Create action should always return 200
     *
     */
    public function testShouldCreate(){
        $this->requestAction('GET', 'Admin\CategoriesController@create');
        $this->assertRequestOk();
    }

    /**
     * Tree action should always return 200
     *
     */
    public function testShouldSaveTreeChangesInSession(){
        $this->withInput(['id'=>'123','state'=>'true'])->requestAction('POST', 'Admin\CategoriesController@tree');
        $this->assertRequestOk();
        $this->assertEquals(array_values(['123'=>'true']), array_values(Session::get('category-tree-state')));
    }

    /**
     * Store action should redirect to index on success
     *
     */
    public function testShouldStore(){
        $input = testCategoryProvider::attributesFor( 'valid_leaf_category' );

        $this->withInput($input)->requestAction('POST', 'Admin\CategoriesController@store');

        $this->assertRedirection(URL::action('Admin\CategoriesController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Store action should redirect to create when invalid
     *
     */
    public function testNotShouldStoreInvalid(){
        $input = testCategoryProvider::attributesFor( 'invalid_leaf_category' );

        $this->withInput($input)->requestAction('POST', 'Admin\CategoriesController@store');

        $this->assertRedirection(URL::action('Admin\CategoriesController@create'));
        $this->assertSessionHas('error');
    }

    /**
     * Edit action should always return 200 if exists
     *
     */
    public function testShouldEdit(){
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $this->requestAction('GET', 'Admin\CategoriesController@edit', ['id'=>$category->_id]);
        $this->assertRequestOk();
    }

    /**
     * Edit action should redirect to index if category doesn't exists
     *
     */
    public function testShouldNotEditNull(){

        $this->requestAction('GET', 'Admin\CategoriesController@edit', ['id'=>'123']);

        $this->assertRedirection(URL::action('Admin\CategoriesController@index'));
        $this->assertSessionHas('flash','não encontrad');
    }

    /**
     * Update action should update existent category and redirect to index
     *
     */
    public function testShouldUpdateExistent(){
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $this->withInput( $category->getAttributes() )
            ->requestAction('PUT', 'Admin\CategoriesController@update', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Update action should redirect to edit form of the same category if update
     * input is invalid
     *
     */
    public function testShouldNotUpdateWithInvalidInput(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );
        $category->name = '';

        $this->withInput( $category->getAttributes() )
            ->requestAction('PUT', 'Admin\CategoriesController@update', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Attach action should redirect to edit of the same resource
     *
     */
    public function testShouldAttachExistent(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );
        $parent = testCategoryProvider::saved( 'another_valid_parent_category' );

        $this->withInput( ['parent'=>(string)$parent->_id] )
            ->requestAction('POST', 'Admin\CategoriesController@attach', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Attach action should redirect to index if the category do not exists
     *
     */
    public function testShouldNotAttachWithInvalidInput(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );

        $this->withInput( ['parent'=>'123123'] )
            ->requestAction('POST', 'Admin\CategoriesController@attach', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@index'));
        $this->assertSessionHas('flash', 'não');
    }

    /**
     * Dettach action should redirect to edit of the same resource
     *
     */
    public function testShouldDetach(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );
        $parent = $category->parents()->first();

        $this->withInput( ['parent'=>(string)$parent->_id] );
        
        $this->requestAction(
                'DELETE', 'Admin\CategoriesController@detach',
                ['id'=>$category->_id, 'parent'=>$parent->_id]
        );

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Add Characteristic action should update existent category and redirect to edit
     *
     */
    public function testShouldAddCharacteristicExistent(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );

        $this->withInput( testCharacteristicProvider::attributesFor( 'valid_option_characteristic' ) )
            ->requestAction('POST', 'Admin\CategoriesController@add_characteristic', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Add Characteristic action should redirect to edit form of the same category if update
     * input is invalid
     *
     */
    public function testShouldNotAddCharacteristicWithInvalidInput(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );

        $this->withInput( ['name'=>'lol'] )
            ->requestAction('POST', 'Admin\CategoriesController@add_characteristic', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Characteristic should be removed from existent category and redirect to edit
     *
     */
    public function testShouldDestroyCharacteristicExistent(){
        $category = testCategoryProvider::saved( 'another_valid_leaf_category' );
        $charac = $category->characteristics()[0];

        $this->requestAction('DELETE', 'Admin\CategoriesController@destroy_characteristic', ['id'=>$category->_id, 'charac_name'=>$charac->name]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    public function testShouldValidateAllProductsForCharacteristics(){
        $category = testCategoryProvider::saved( 'valid_leaf_category' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->requestAction('GET', 'Admin\CategoriesController@validate_products', ['id'=>$category->_id]);
        $this->assertRedirection(URL::action('Admin\ProductsController@invalids', ['category_id'=>$category->_id]));
    }

}
