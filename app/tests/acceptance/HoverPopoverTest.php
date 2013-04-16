<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class HoverPopoverTest extends AcceptanceTestCase
{
    public function setUp()
    {
        parent::setup();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
    }

    public function testShouldCanSeePopoverWhenHoverElement()
    {
        $category = f::create( 'Category', ['kind'=>'leaf'] );
        $product = f::create( 'Product', ['category' => $category->_id] );
        $content = f::create( 'Content' );

        $this->browser
            ->open(URL::action('Admin\ProductsController@index'))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));

        $this->browser
            ->open(URL::action('Admin\ProductsController@edit', ['id' => $product->_id]))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));

        $this->browser
            ->open(URL::action('Admin\ContentsController@show', ['id' => $content->_id]))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));

        $this->browser
            ->open(URL::action('Admin\CategoryController@edit', ['id' => $category->_id]))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));
    }
}
