<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class HideCategoryTest extends Zizaco\TestCases\AcceptanceTestCase
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

    public function testShouldSetCategoryAsHidden()
    {
        $parentCategory = f::create( 'Category' );
        $category = f::create( 'Category', ['name'=>'SomeEspecificName', 'parent'=>[$parentCategory->_id]] );

        // Check if the category is visible
        $this->browser
            ->open(URL::action('CategoriesController@show', ['id'=>$parentCategory->_id]));

        $this->assertBodyHasHtml( $category->name );

        // Set as invisible
        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::id('checkbox-hidden'))
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        // Check if the category is hidden
        $this->browser
            ->open(URL::action('CategoriesController@show', ['id'=>$parentCategory->_id]));

        $this->assertBodyHasNotHtml( $category->name );

        // When user try to access the direct link to the category
        // should redirect to index
        $this->browser
            ->open(URL::action('CategoriesController@show', ['id'=>$category->_id]));

        $this->assertLocation( URL::action('HomeController@index') );
    }
}
