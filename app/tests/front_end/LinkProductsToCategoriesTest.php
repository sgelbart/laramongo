<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class LinkProductsToCategoriesTest extends AcceptanceTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
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

        $this->assertEquals((string)'leaf', (string)$category->type);
    }

    public function testShouldSetCategoryAsNonLeaf()
    {
        $category = f::create( 'Category', ['type'=>'leaf'] );

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::id('radio-kind-blank'))
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $category = Category::find($category->_id);

        $this->assertNotEquals('leaf', (string)$category->type);
    }

    public function testShouldSetLeafCategoryOfProduct()
    {
        $leafA = f::create( 'Category', ['type'=>'leaf'] );
        $leafB = f::create( 'Category', ['type'=>'leaf'] );
        $product = testProductProvider::saved('simple_valid_product');

        $this->browser
            ->open(URL::action('Admin\ProductsController@edit', ['id'=>$product->_id]))
            ->select(l::IdOrName('category'), $leafB->name)
            ->click(l::id('submit-save-product'))
            ->waitForPageToLoad(1000);

        $product = Product::first($product->_id);
        $this->assertEquals($leafB, $product->category());
    }
}
