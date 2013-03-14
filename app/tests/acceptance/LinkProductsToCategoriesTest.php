<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class LinkProductsToCategoriesTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    public function testShouldSetCategoryAsLeaf()
    {
        $category = f::create( 'Category' );

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::id('radio-kind-leaf'))
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $category = Category::find($category->_id);

        $this->assertEquals((string)'leaf', (string)$category->kind);
    }

    public function testShouldSetCategoryAsNonLeaf()
    {
        $category = f::create( 'Category', ['kind'=>'leaf'] );

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::id('radio-kind-blank'))
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $category = Category::find($category->_id);

        $this->assertNotEquals('leaf', (string)$category->kind);
    }
}
