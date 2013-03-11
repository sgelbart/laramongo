<?php

class AdminProductsTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        // Set session
        Input::setSessionStore(app()['session']);

        $this->aExistentCategory();
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldIndex(){
        $crawler = $this->requestAction('GET', 'Admin\ProductsController@index');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Create action should always return 200
     *
     */
    public function testShouldCreate(){
        $crawler = $this->requestAction('GET', 'Admin\ProductsController@create');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Store action should redirect to index on success
     *
     */
    public function testShouldStore(){
        $input = $this->aExistentProduct()->attributes;
        unset($input['id']);

        // Post parameters
        Input::replace( $input );

        $crawler = $this->requestAction('POST', 'Admin\ProductsController@store');

        // Index location
        $location = 'http://:/'.URL::action('Admin\ProductsController@index');

        // Should redirect with success message
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Store action shoud redirect to create when input
     * is not valid
     */
    public function testShouldNotStoreInvalid(){
        // Simulates empty fields
        Input::replace( array() );

        // The create form location
        $location = 'http://:/'.URL::action('Admin\ProductsController@create');

        $crawler = $this->requestAction('POST', 'Admin\ProductsController@store');
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Show action should redirect to edit action of the same product id
     *
     */
    public function testShouldShow(){
        // The edit form location
        $location = 'http://:/'.URL::action('Admin\ProductsController@edit', ['id' => 0]);

        $crawler = $this->requestAction('GET', 'Admin\ProductsController@show', ['id' => 0]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * The edit action should return 200 when the id exists in database
     *
     */
    public function testShouldEditExistent(){
        $product = $this->aExistentProduct();

        $crawler = $this->requestAction('GET', 'Admin\ProductsController@edit', ['id'=>$product->id]);
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Edit action should redirect to index if product doesn't exists
     *
     */
    public function testShouldNotEditNull(){
        $invalid_id = 0;

        // The edit form location
        $location = 'http://:/'.URL::action('Admin\ProductsController@index');

        $crawler = $this->requestAction('GET', 'Admin\ProductsController@edit', ['id'=>$invalid_id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Update action should update existent product and redirect to index
     *
     */
    public function testShouldUpdateExistent(){
        $product = $this->aExistentProduct();

        // Simulates valid input
        Input::replace( $product->attributes );

        // Index location
        $location = 'http://:/'.URL::action('Admin\ProductsController@index');

        $crawler = $this->requestAction('PUT', 'Admin\ProductsController@update', ['id'=>$product->id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Update action should redirect to edit form of the same product if update
     * input is invalid
     *
     */
    public function testShouldNotUpdateNull(){
        $product = $this->aExistentProduct();

        // Simulates empty fields
        Input::replace( array('name'=>'') );

        // The edit form location
        $location = 'http://:/'.URL::action('Admin\ProductsController@edit', ['id' => $product->id]);

        $crawler = $this->requestAction('PUT', 'Admin\ProductsController@update', ['id'=>$product->id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Destroy action should redirect to index
     *
     */
    public function testShouldDestroy(){
        $product = $this->aExistentProduct();

        $crawler = $this->requestAction('DELETE', 'Admin\ProductsController@destroy',['id' => $product->id]);
        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    /**
     * Import action should always return 200 
     *
     */
    public function testShowImportDialog(){
        $crawler = $this->requestAction('GET', 'Admin\ProductsController@import');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Request an URL by the action name
     * 
     * @param string $method
     * @param string $action
     *
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public function requestAction( $method, $action, $params = array())
    {
        $action_url = URL::action($action, $params);
        
        if ($action_url == '')
            $this->assertTrue(false, $action.' does not exist');

        return $this->client->request( $method, $action_url );
    }

    /**
     * Returns an product that "exists in database".
     *
     * @param string $name
     * @return Product
     */
    private function aExistentProduct( $name = 'something' )
    {
        $product = new Product;

        $product->name = 'something';
        $product->family = 'existentfamily';
        $product->price = 12.34;
        $product->desc = 'the description';

        $product->save();

        return $product;
    }

    /**
     * Returns an category that "exists in database".
     *
     * @param string $name
     * @return Category
     */
    private function aExistentCategory( $name = 'existentfamily' )
    {
        $category = new Category;

        $category->name = $name;
        $category->description = 'somedescription';
        $category->image = 'aimage.jpg';

        $category->save();

        return $category;
    }
}
