<?php

class AdminCategoriesTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        // Set session
        Input::setSessionStore(app()['session']);
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldIndex(){
        $crawler = $this->requestAction('GET', 'Admin\CategoriesController@index');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Create action should always return 200
     *
     */
    public function testShouldCreate(){
        $crawler = $this->requestAction('GET', 'Admin\CategoriesController@create');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Store action should redirect to index on success
     *
     */
    public function testShouldStore(){
        $input = $this->aExistentCategory()->attributes;
        unset($input['id']);

        // Post parameters
        Input::replace( $input );

        $crawler = $this->requestAction('POST', 'Admin\CategoriesController@store');

        // Index location
        $location = 'http://:/'.URL::action('Admin\CategoriesController@index');

        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Edit action should return 200 then id exists in database
     */
    public function testShouldEditExistent()
    {
        $category = $this->aExistentCategory();

        $crawler = $this->requestAction('GET', 'Admin\CategoriesController@edit', ['id'=>$category->id]);
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Edit action should redirect to index if category doesn't exists
     *
     */
    public function testShouldNotEditNull(){
        $invalid_id = 0;

        // The edit form location
        $location = 'http://:/'.URL::action('Admin\CategoriesController@index');

        $crawler = $this->requestAction('GET', 'Admin\CategoriesController@edit', ['id'=>$invalid_id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Update action should update existent category and redirect to index
     *
     */
    public function testShouldUpdateExistent(){
        $category = $this->aExistentCategory();

        // Simulates valid input
        Input::replace( $category->attributes );

        // Index location
        $location = 'http://:/'.URL::action('Admin\CategoriesController@index');

        $crawler = $this->requestAction('PUT', 'Admin\CategoriesController@update', ['id'=>$category->id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Update action should redirect to edit form of the same category if update
     * input is invalid
     *
     */
    public function testShouldNotUpdateNull(){
        $category = $this->aExistentCategory();

        // Simulates empty fields
        Input::replace( array('name'=>'') );

        // The edit form location
        $location = 'http://:/'.URL::action('Admin\CategoriesController@edit', ['id' => $category->id]);

        $crawler = $this->requestAction('PUT', 'Admin\CategoriesController@update', ['id'=>$category->id]);
        $this->assertTrue($this->client->getResponse()->isRedirect($location));
    }

    /**
     * Destroy action should redirect to index
     *
     */
    public function testShouldDestroy(){
        $category = $this->aExistentCategory();

        $crawler = $this->requestAction('DELETE', 'Admin\CategoriesController@destroy',['id' => $category->id]);
        $this->assertTrue($this->client->getResponse()->isRedirection());
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
     * Returns an category that "exists in database".
     *
     * @param string $name
     * @return Category
     */
    private function aExistentCategory( $name = 'something' )
    {
        $category = new Category;

        $category->name = 'something';
        $category->description = 'somedescription';
        $category->image = 'aimage.jpg';

        $category->save();

        return $category;
    }
}
