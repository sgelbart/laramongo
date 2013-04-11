<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ManageArticleTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'contents' );
    }

    public function testShouldCreateArticle()
    {
        $attr = testContentProvider::attributesFor( 'valid_article' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::id('btn-create-new-content'))
            ->click(l::id('btn-create-new-article'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attr['name'])
            ->type(l::IdOrName('slug'), $attr['slug'])
            ->type(l::IdOrName('article'), $attr['article'])
            ->click(l::id('submit-form'))
            ->open('/admin/contents')
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-index'), $attr['name']);
    }

    public function testShouldEditArticle()
    {
        $content = testContentProvider::saved( 'valid_article' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), 'Bacon')
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation( URL::action('Admin\ContentsController@index') );
        $this->assertElementHasText(l::id('content-index'), 'Bacon');

        $this->browser
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), '')
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation( URL::action('Admin\ContentsController@edit', ['id'=>$content->_id]) );
    }
}
