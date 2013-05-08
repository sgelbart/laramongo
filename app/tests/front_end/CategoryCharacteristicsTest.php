<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class CategoryCharacteristicsTest extends AcceptanceTestCase
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

    public function testShouldSetCategoryCharacteristics()
    {
        $category = f::create( 'Category', ['type'=>'leaf'] );

        // Add first Characteristic
        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->select(l::IdOrName('type'), 'Opções')
            ->type(l::id('characteristic-name'), 'Charac')
            ->type(l::IdOrName('layout-pre'), 'about')
            ->type(l::IdOrName('layout-pos'), 'kg')
            ->type(l::IdOrName('values'), 'Madeira, Metal, Plastico')
            ->click(l::id('submit-create-characteristic'))
            ->waitForPageToLoad(1000);

        $category = Category::first($category->_id);
        $charac = $category->characteristics()[0];

        $this->assertEquals( 'Charac', $charac->name );
        $this->assertEquals( 'about', $charac->getAttribute('layout-pre') );
        $this->assertEquals( 'kg', $charac->getAttribute('layout-pos') );
        $this->assertEquals( 'option', $charac->type );
        $this->assertEquals( ['Madeira', 'Metal', 'Plastico'], $charac->values );

        // Add second characteristic
        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->select(l::IdOrName('type'), 'Numero')
            ->type(l::id('characteristic-name'), 'Size')
            ->type(l::IdOrName('layout-pos'), 'metros')
            ->click(l::id('submit-create-characteristic'))
            ->waitForPageToLoad(1000);

        $category = Category::first($category->_id);
        $charac = $category->characteristics()[1];

        $this->assertEquals( 'Size', $charac->name );
        $this->assertEquals( '', $charac->getAttribute('layout-pre') );
        $this->assertEquals( 'metros', $charac->getAttribute('layout-pos') );
        $this->assertEquals( 'int', $charac->type );
    }

    public function testShouldSetProductCharacteristicValue()
    {
        $category = f::create( 'Category', ['type'=>'leaf'] );
        $characA = f::instance( 'Characteristic' );
        $characB = f::instance( 'Characteristic' );

        $category->embedToCharacteristics( $characA );
        $category->embedToCharacteristics( $characB );
        $category->save();

        $product = testProductProvider::instance('simple_valid_product');
        $product->category = $category->_id;
        $product->save();

        $this->browser
            ->open(URL::action('Admin\ProductsController@edit', ['id'=>$product->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->type(l::IdOrName(clean_case($characA->name)), '1')
            ->type(l::IdOrName(clean_case($characB->name)), '2')
            ->click(l::id('submit-save-product-characteristics'))
            ->waitForPageToLoad(1000);

        $product = Product::first($product->_id);

        $this->assertEquals('1', $product->characteristics[clean_case($characA->name)]);
        $this->assertEquals('2', $product->characteristics[clean_case($characB->name)]);
    }
}
