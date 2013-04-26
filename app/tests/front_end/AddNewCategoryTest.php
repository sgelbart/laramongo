<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class AddNewCategoryTest extends Zizaco\TestCases\AcceptanceTestCase
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

    public function testShouldCreateCategory()
    {
        $attr = f::attributesFor('Category');

        $this->browser
            ->open('/admin/categories')
            ->click(l::id('btn-create-new-category'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attr['name'])
            ->type(l::IdOrName('description'), $attr['description'])
            ->type(l::IdOrName('template'), 'responsive')
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('categories-table'), $attr['name']);
        $category = Category::first();
        $this->assertEquals('responsive', $category->template );
    }

    public function testShouldNotCreateInvalidCategory()
    {
        $attr = f::attributesFor('Category');

        $this->browser
            ->open('/admin/categories')
            ->click(l::id('btn-create-new-category'))
            ->waitForPageToLoad(1000)
            // Don't type the name
            ->type(l::IdOrName('description'), $attr['description'])
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation(URL::action('Admin\CategoriesController@create'));
    }
}
