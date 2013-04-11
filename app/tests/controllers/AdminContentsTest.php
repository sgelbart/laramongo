<?php

use Mockery as m;

class AdminContentsTest extends ControllerTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'contents' );
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Index action should always return 200
     *
     */
    public function testShouldIndex(){
        $this->requestAction('GET', 'Admin\ContentsController@index');
        $this->assertRequestOk();
    }

    /**
     * Create Article action should always return 200
     *
     */
    public function testShouldCreateArticle(){
        $this->requestAction('GET', 'Admin\ContentsController@createArticle');
        $this->assertRequestOk();
    }

    /**
     * Create Image action should always return 200
     *
     */
    public function testShouldCreateImage(){
        $this->requestAction('GET', 'Admin\ContentsController@createImage');
        $this->assertRequestOk();
    }

    /**
     * Create Video action should always return 200
     *
     */
    public function testShouldCreateVideo(){
        $this->requestAction('GET', 'Admin\ContentsController@createVideo');
        $this->assertRequestOk();
    }

    /**
     * Edit Content action should return 200 if exists
     *
     */
    public function testShouldEditExistingContent(){
        // Article
        $content = testContentProvider::saved( 'valid_article' );

        $this->requestAction('GET', 'Admin\ContentsController@edit', ['id'=>$content->_id]);
        $this->assertRequestOk();

        // Video
        $content = testContentProvider::saved( 'valid_video' );

        $this->requestAction('GET', 'Admin\ContentsController@edit', ['id'=>$content->_id]);
        $this->assertRequestOk();
    }

    /**
     * Edit Content action should redirect to index if content doesn't exists exists
     *
     */
    public function testShouldNotEditNonExistingContent(){
        $this->requestAction('GET', 'Admin\ContentsController@edit', ['id'=>'lol']);
        
        $this->assertRedirection(URL::action('Admin\ContentsController@index'));
        $this->assertSessionHas('flash','não encontrad');
    }

    /**
     * Store action should return 200 if success
     *
     */
    public function testShouldStoreValidContent(){
        $input = testContentProvider::attributesFor( 'valid_article' );
        unset( $input['_id'] );

        $this->withInput($input)->requestAction('POST', 'Admin\ContentsController@store');

        $savedContent = Content::first();

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$savedContent->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Store action should not return if the data is invalid
     *
     */
    public function testShouldNotStoreInvalidContent(){
        $input = testContentProvider::attributesFor( 'invalid_article' );
        unset( $input['_id'] );

        $this->withInput($input)->requestAction('POST', 'Admin\ContentsController@store');

        $this->assertRedirection(URL::action('Admin\ContentsController@createArticle'));
        $this->assertSessionHas('error');
    }

    /**
     * Store action should try to attach file
     *
     */
    public function testShouldAttachWhenStore(){
        $input = testContentProvider::attributesFor( 'valid_image' );
        unset( $input['_id'] );

        // A mocked image that should be attached
        $image = m::mock('UploadedFile');
        $image->shouldReceive('move')->once();
        $input['image_file'] = $image;

        $this->withInput($input)->requestAction('POST', 'Admin\ContentsController@store');

        $savedContent = Content::first();

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$savedContent->_id]));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Update action should redirect to index if success
     *
     */
    public function testShouldUpdateValidContent()
    {
        $content = testContentProvider::saved( 'valid_article' );

        $input = $content->attributes;

        $this->withInput($input)->requestAction('PUT', 'Admin\ContentsController@update', ['id'=>$content->_id]);

        $this->assertRedirection(URL::action('Admin\ContentsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Update action should attach image file if success
     *
     */
    public function testShouldUpdateShouldAttachImage()
    {
        $content = testContentProvider::saved( 'valid_image' );

        $input = $content->attributes;
        
        // A mocked image that should be attached
        $image = m::mock('UploadedFile');
        $image->shouldReceive('move')->once();
        $input['image_file'] = $image;

        $this->withInput($input)->requestAction('PUT', 'Admin\ContentsController@update', ['id'=>$content->_id]);

        $this->assertRedirection(URL::action('Admin\ContentsController@index'));
        $this->assertSessionHas('flash','sucesso');
    }

    /**
     * Update action should redirect back to edit on failure
     *
     */
    public function testShouldNotUpdateContentWithInvalidData()
    {
        $content = testContentProvider::saved( 'valid_article' );

        $input = $content->attributes;
        $input['name'] = ""; // Invalid name

        $this->withInput($input)->requestAction('PUT', 'Admin\ContentsController@update', ['id'=>$content->_id]);

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id]));
        $this->assertSessionHas('error');
    }

    /**
     * Get existent tags
     *
     */
    public function testShouldGetExistentTags(){
        testContentProvider::saved( 'valid_article' );

        $this->requestAction('GET', 'Admin\ContentsController@tags', ['term'=>'inter']);
        $this->assertRequestOk(); 
    }

    public function testShouldRelateProduct(){
        $content = testContentProvider::saved( 'valid_article' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->requestAction(
            'POST', 'Admin\ContentsController@addProduct',
            ['id'=>$content->_id, 'product_id'=>$product->_id] 
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash','sucesso');
    }
}
