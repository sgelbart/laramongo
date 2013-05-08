<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class HoverPopoverTest extends IntegrationTestCase
{
    use TestHelper;

    public function setUp()
    {
        parent::setup();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'contents' );
    }

    public function testShouldCanSeePopoverWhenHoverElement()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $content = testContentProvider::saved( 'valid_article' );

        $content->attachToCategories($product->category());
        $content->save();

        $this->browser
            ->open(URL::action('Admin\ProductsController@index'))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));

        $this->browser
            ->open(URL::action('Admin\ContentsController@edit', ['id' => $content->_id]))
            ->click(l::linkContaining('Relacionamento'))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));

        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id' => $product->category()->_id]))
            ->click(l::linkContaining('Hierarquia'))
            ->mouseOver(l::css('span[data-with-popover]'));

        $this->assertNotNull(l::css('span[data-with-popover]:visible'));
    }
}
