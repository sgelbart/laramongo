<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class EditCategoryTest extends Zizaco\TestCases\IntegrationTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    public function testShouldUpdateCategory()
    {
        $category = f::create('Category');

        $new_name = 'NewName';

        $this->browser
            ->open('/admin/categories')
            ->click(l::css('#edit-cat-'.$category->_id))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $new_name)
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('categories-table'), $new_name);
    }

    public function testShouldNotUpdateCategoryWithInvalidData()
    {
        $category = f::create('Category');

        $invalid_name = '';

        $this->browser
            ->open('/admin/categories')
            ->click(l::css('#edit-cat-'.$category->_id))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $invalid_name)
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]));

        $this->browser->open('/admin/categories');

        $this->assertElementHasText(l::id('categories-table'), $category->name);
    }
}
