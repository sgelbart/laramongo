<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ModifyCategoryHierarchy extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    public function testAttachParentToCategory()
    {
        $category = f::create( 'Category' );
        $parent = f::create( 'Category' );

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Hierarquia'))
            ->select(l::IdOrName('parent'), $parent->name)
            ->click(l::id('submit-attach-category'))
            ->waitForPageToLoad(1000)
            ->click(l::linkContaining('Hierarquia'));

        $this->assertElementHasText(l::id('hierarchy-table'), $parent->name);
    }
}
