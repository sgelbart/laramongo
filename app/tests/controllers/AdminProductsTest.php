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
        $input = testProductProvider::attributesFor( 'simple_valid_product' );

        $this->withInput($input)->requestAction('POST', 'Admin\ProductsController@store');

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Store action shoud redirect to create when input
     * is not valid
     */
    public function testShouldNotStoreInvalid(){
        $input = $input = testProductProvider::attributesFor( 'simple_invalid_product' );

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
        $product = testProductProvider::saved( 'simple_valid_product' );

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
        $product = testProductProvider::saved( 'simple_valid_product' );

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
        $product = testProductProvider::saved( 'simple_valid_product' );
        $product->name = '';

        $this->withInput( $product->getAttributes() )
            ->requestAction('PUT', 'Admin\ProductsController@update', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id'=>$product->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Update characteristics action should update existent product and redirect to index
     *
     */
    public function testShouldUpdateCharacteristicsOfExistent(){
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->withInput( ['capacidade'=>5] )
            ->requestAction('PUT', 'Admin\ProductsController@characteristic', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Destroy action should redirect to index
     *
     */
    public function testShouldDestroy(){
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->requestAction('DELETE', 'Admin\ProductsController@destroy', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','sucess');
    }

    /**
     * Destroy action should redirect to index with error message
     * when failed to delete product. I.E: A product that compose a
     * conjugated
     *
     */
    public function testShouldShowErrorWhenDestroyFails(){
        
        $conjProduct = testConjugatedProductProvider::saved('simple_conjugated_product');

        $product = $conjProduct->products()->first();

        $this->requestAction('DELETE', 'Admin\ProductsController@destroy', ['id'=>$product->_id]);

        $this->assertRedirection(URL::action('Admin\ProductsController@index'));
        $this->assertSessionHas('flash','conjugado');
    }

    /**
     * Import action should always return 200 
     *
     */
    public function testShowImportDialog(){
        $this->requestAction('GET', 'Admin\ProductsController@import');
        $this->assertRequestOk();
    }

    /**
     * Invalids action should always return 200 
     *
     */
    public function testDisplayInvalids(){
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $this->requestAction('GET', 'Admin\ProductsController@invalids', ['category_id'=>$category->_id]);
        $this->assertRequestOk();
    }

    /**
     * Fix action should update existent product
     *
     */
    public function testShouldFixExistent(){
        $product = testProductProvider::saved( 'simple_valid_product' );
        $product->details = ['color'=>'red'];
        $product->save(true);

        $this->withInput( ['color'=>'blue'] )
            ->requestAction('PUT', 'Admin\ProductsController@fix', ['id'=>$product->_id]);

        $this->assertRequestOk();
    }

    /**
     * Fix action should return 404 if the product doesn't exist
     *
     */
    public function testShouldNotFixNonExistent(){
        $this->withInput( ['char'=>'anything'] )
            ->requestAction('PUT', 'Admin\ProductsController@fix', ['id'=>'lol']);

        $this->assertStatusCode('404');
    }

    /**
     * Toggle action should simply return 200
     *
     */
    public function testToggleDeactivation()
    {
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->requestAction('PUT', 'Admin\ProductsController@toggle', ['id'=>$product->_id]);
        $this->assertRequestOk();
    }

    /**
     * Should add a product to a composition of a conjugated one
     *
     */
    public function testAddToConjugated()
    {
        $product = testProductProvider::saved( 'product_with_details' );
        $conjProduct = testConjugatedProductProvider::saved('simple_conjugated_product');

        $this->requestAction('PUT', 'Admin\ProductsController@addToConjugated', ['conj_id'=>$conjProduct->_id, 'id'=>$product->_id]);
        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id'=>$conjProduct->_id, 'tab'=>'product-conjugation']));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Should remove successfuly a product from a conjugated product
     *
     */
    public function testRemoveFromConjugated()
    {
        $conjProduct = testConjugatedProductProvider::saved('simple_conjugated_product');
        
        // Add a new product since a conjugated need at least 2 products
        $conjProduct->attachToConjugated( testProductProvider::saved( 'product_with_details' )->_id );
        $conjProduct->save();

        // Remove one of the three attached products
        $product = $conjProduct->products()->first();

        $this->requestAction('PUT', 'Admin\ProductsController@removeFromConjugated', ['conj_id'=>$conjProduct->_id, 'id'=>$product->_id]);
        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id'=>$conjProduct->_id, 'tab'=>'product-conjugation']));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Fail to remove product from conjugated product
     *
     */
    public function testFailToRemoveFromConjugated()
    {
        $conjProduct = testConjugatedProductProvider::saved('simple_conjugated_product');
        $product = $conjProduct->products()->first();

        $this->requestAction('PUT', 'Admin\ProductsController@removeFromConjugated', ['conj_id'=>$conjProduct->_id, 'id'=>$product->_id]);
        $this->assertRedirection(URL::action('Admin\ProductsController@edit', ['id'=>$conjProduct->_id, 'tab'=>'product-conjugation']));
        $this->assertSessionHas('error');
    }
}
