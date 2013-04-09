<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

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
}
