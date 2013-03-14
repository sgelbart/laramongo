<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ModifyCategoryHierarchyTest extends AcceptanceTestCase
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

    public function testDeattachParentOfCategory()
    {
        $parentA = f::create( 'Category' ); // will be detached
        $parentB = f::create( 'Category' ); // will not be detached
        $category = f::create( 'Category' );
        $category->attachToParents($parentA);
        $category->attachToParents($parentB);
        $category->save();

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Hierarquia'))
            ->click(l::linkContaining('Remover relação'))
            ->waitForPageToLoad(1000)
            ->click(l::linkContaining('Hierarquia'));

        $this->assertElementHasNotText(l::id('hierarchy-table'), $parentA->name);
        $this->assertElementHasText(l::id('hierarchy-table'), $parentB->name);
    }
}
