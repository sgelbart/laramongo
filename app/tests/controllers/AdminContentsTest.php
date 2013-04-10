<?php

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
     * Edit Content action should return 200 if exists
     *
     */
    public function testShouldEditExistingContent(){
        $content = testContentProvider::saved( 'valid_article' );

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

        $this->assertRedirection(URL::action('Admin\ContentsController@index'));
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
}
