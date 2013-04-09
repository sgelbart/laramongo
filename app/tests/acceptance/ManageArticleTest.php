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
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-index'), $attr['name']);
    }
}
