<?php

class ProductsTest extends TestCase
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
     * Show action should return 200 then id exists in database
     */
    public function testShouldShowExistent()
    {
        $product = $this->aExistentProduct();

        $crawler = $this->requestAction('GET', 'ProductsController@show', ['id'=>$product->id]);
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Show action should redirect to index if product doesn't exists
     *
     */
    public function testShouldNotShowNull(){
        $invalid_id = 0;

        // The website index location
        $location = 'http://:/';

        $crawler = $this->requestAction('GET', 'ProductsController@show', ['id'=>$invalid_id]);

        $this->assertTrue($this->client->getResponse()->isRedirect($location));
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
        $product->family = $this->aExistentCategory()->name;
        $product->description = 'somedescription';

        $product->save();

        return $product;
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
