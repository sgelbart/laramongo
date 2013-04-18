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

        // Make sure that search and paginate will be called at leas
        // once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('search')->once()->passthru();
        $contentRepo->shouldReceive('paginate')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        // Do request
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

        // Make sure that first is called twice
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('first')->twice()->passthru();
        App::instance("ContentRepository", $contentRepo);

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

        // Make sure that repo->first is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('first')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        // Request
        $this->requestAction('GET', 'Admin\ContentsController@edit', ['id'=>'lol']);

        $this->assertRedirection(URL::action('Admin\ContentsController@index'));
        $this->assertSessionHas('flash','não encontrad');
    }

    /**
     * Store action should return 200 if success
     *
     */
    public function testShouldStoreValidContent(){

        // Make sure that repo->createNew is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('createNew')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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

        // Make sure that repo->createNew is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('createNew')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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

        // Make sure that repo->createNew is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('createNew')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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
        // Make sure that repo->first and repo->update is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('first')->once()->passthru();
        $contentRepo->shouldReceive('update')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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
        // Make sure that repo->first and repo->update is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('first')->once()->passthru();
        $contentRepo->shouldReceive('update')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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
        // Make sure that repo->first and repo->update is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('first')->once()->passthru();
        $contentRepo->shouldReceive('update')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

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
        // Make sure that repo->existentTags is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('existentTags')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        testContentProvider::saved( 'valid_article' );

        $this->requestAction('GET', 'Admin\ContentsController@tags', ['term'=>'inter']);
        $this->assertRequestOk();
    }

    public function testShouldRelateProduct(){

        // Make sure that repo->relateToProduct is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('relateToProduct')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $content = testContentProvider::saved( 'valid_article' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->requestAction(
            'POST', 'Admin\ContentsController@addProduct',
            ['id'=>$content->_id, 'product_id'=>$product->_id]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash','sucesso');
    }

    public function testShouldntRelateInvalidProducts(){

        // Make sure that repo->relateToProduct is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('relateToProduct')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $content = testContentProvider::saved( 'valid_article' );
        $lms = 'NONEXISTINGLM, ANOTHERLM, LMDOESNTEXIST';

        $this->requestAction(
            'POST', 'Admin\ContentsController@addProduct',
            ['id'=>$content->_id, 'product_id'=>$lms]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash_error');
    }

    public function testShouldTagProductToImage(){

        // Make sure that repo->tagToProduct is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('tagToProduct')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        // attach Product to ImageContent
        $content = testContentProvider::saved( 'valid_image' );
        $product = testProductProvider::saved( 'simple_valid_product' );
        $content->attachToProducts( $product );
        $content->save();

        // Request
        $this->withInput(['x'=>20,'y'=>30,'product_id'=>$product->_id])->requestAction(
            'POST', 'Admin\ContentsController@tagProduct',
            ['id'=>$content->_id]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-image-tagging']));
        $this->assertSessionHas('flash','sucesso');
    }

    public function testShouldNotTagNonRelatedProductToImage(){

        // Make sure that repo->tagToProduct is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('tagToProduct')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        // Don't attach product to ImageContent
        $content = testContentProvider::saved( 'valid_image' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        // Request
        $this->withInput(['x'=>20,'y'=>30, 'product_id'=>$product->_id])->requestAction(
            'POST', 'Admin\ContentsController@tagProduct',
            ['id'=>$content->_id]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-image-tagging']));
        $this->assertSessionHas('flash_error');
    }

    public function testShouldRemoveRelatedProduct(){

        // Make sure that repo->removeProduct is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('removeProduct')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $content = testContentProvider::saved( 'valid_article' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $content->attachToProducts( $product->_id );

        $this->requestAction(
            'DELETE', 'Admin\ContentsController@removeProduct',
            ['id'=>$content->_id, 'product_id'=>$product->_id]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash','sucesso');
    }

    public function testShouldRelateCategory(){

        // Make sure that repo->relateToCategory is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('relateToCategory')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $content = testContentProvider::saved( 'valid_article' );
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $this->requestAction(
            'POST', 'Admin\ContentsController@addCategory',
            ['id'=>$content->_id, 'category_id'=>$category->_id] 
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash','sucesso');
    }

    public function testShouldRemoveRelatedCategory(){

        // Make sure that repo->removeCategory is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('removeCategory')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $content = testContentProvider::saved( 'valid_article' );
        $category = testCategoryProvider::saved( 'valid_leaf_category' );

        $content->attachToCategorys( $category->_id );

        $this->requestAction(
            'DELETE', 'Admin\ContentsController@removeCategory',
            ['id'=>$content->_id, 'category_id'=>$category->_id]
        );

        $this->assertRedirection(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-relations']));
        $this->assertSessionHas('flash','sucesso');
    }
}
