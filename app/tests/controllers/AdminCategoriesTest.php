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

    /**
     * Edit action should always return 200 if exists
     *
     */
    public function testShouldEdit(){
        $category = f::create( 'Category' );

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
        $category = f::create( 'Category' );

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
        $category = f::create( 'Category' );
        $category->name = '';

        $this->withInput( $category->getAttributes() )
            ->requestAction('PUT', 'Admin\CategoriesController@update', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldDisplayTree(){
        $this->requestAction('GET', 'Admin\CategoriesController@tree');
        $this->assertRequestOk();
    }

    /**
     * Attach action should redirect to edit of the same resource
     *
     */
    public function testShouldAttachExistent(){
        $category = f::create( 'Category' );
        $parent = f::create( 'Category' );

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
        $category = f::create( 'Category' );

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
        $parent = f::create( 'Category' );
        $category = f::create( 'Category' );
        $category->attachToParents($parent);
        $category->save();

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
        $category = f::create( 'Category' );

        $this->withInput( f::attributesFor( 'Characteristic' ) )
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
        $category = f::create( 'Category' );
        $category->name = '';

        $this->withInput( $category->getAttributes() )
            ->requestAction('POST', 'Admin\CategoriesController@add_characteristic', ['id'=>$category->_id]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Characteristic should be removed from existent category and redirect to edit
     *
     */
    public function testShouldDestroyCharacteristicExistent(){
        $category = f::create( 'Category' );
        $charac = f::create( 'Characteristic' );

        $category->embedToCharacteristics( $charac );
        $category->save();

        $this->requestAction('DELETE', 'Admin\CategoriesController@destroy_characteristic', ['id'=>$category->_id, 'charac_name'=>$charac->name]);

        $this->assertRedirection(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

}
