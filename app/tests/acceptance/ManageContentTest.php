<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ManageContentTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'contents' );
    }

    public function testShouldCreateArticle()
    {
        $attributes = testContentProvider::attributesFor( 'valid_article' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::id('btn-create-new-content'))
            ->click(l::id('btn-create-new-article'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attributes['name'])
            ->type(l::IdOrName('slug'), $attributes['slug'])
            ->type(l::IdOrName('article'), $attributes['article'])
            ->click(l::id('submit-form'))
            ->open('/admin/contents')
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-index'), $attributes['name']);
    }

    public function testShouldCreateVideo()
    {
        $attributes = testContentProvider::attributesFor( 'valid_video' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::id('btn-create-new-content'))
            ->click(l::id('btn-create-new-video'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attributes['name'])
            ->type(l::IdOrName('slug'), $attributes['slug'])
            ->type(l::IdOrName('youTubeId'), $attributes['youTubeId'])
            ->click(l::id('submit-form'))
            ->open('/admin/contents')
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-index'), $attributes['name']);
    }

    public function testShouldCreateImageAndUploadImage()
    {
        $attributes = testContentProvider::attributesFor( 'valid_image' );

        $imageFile = 'file://'.__DIR__.'/../assets/image.jpg';

        $this->browser
            ->open('/admin/contents')
            ->click(l::id('btn-create-new-content'))
            ->click(l::id('btn-create-new-image'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attributes['name'])
            ->type(l::IdOrName('slug'), $attributes['slug'])
            ->attachFile(l::IdOrName('image_file'), $imageFile)
            ->click(l::id('submit-form'))
            ->open('/admin/contents')
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-index'), $attributes['name']);
        $imageContent = ImageContent::first();
        $this->assertNotNull($imageContent->image);
    }

    public function testShouldEditArticle()
    {
        $content = testContentProvider::saved( 'valid_article' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), 'Bacon')
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation( URL::action('Admin\ContentsController@index') );
        $this->assertElementHasText(l::id('content-index'), 'Bacon');

        $this->browser
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), '')
            ->click(l::id('submit-form'))
            ->waitForPageToLoad(1000);

        $this->assertLocation( URL::action('Admin\ContentsController@edit', ['id'=>$content->_id]) );
    }

    public function testShouldRelateContentToProducts()
    {
        $content = testContentProvider::saved( 'valid_article' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->click(l::css('[data-tab-of=content-relations]'))
            ->type(l::id('product-relation-quicksearch'), substr($product->name,0,5) )
            ->typeKeys(l::id('product-relation-quicksearch'), substr($product->name,0,5) );
            sleep(1); // Wait for ajax :(

        $this->browser
            ->click(l::css('#product-index a.btn-relate-product'))
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-relations-table'), (string)$product->_id);

        $content = Content::first($content->_id);
        $this->assertContains($product->_id, $content->products);
    }

    public function testShouldRelateContentToCategories()
    {
        $content = testContentProvider::saved( 'valid_article' );
        $category = testCategoryProvider::saved( 'valid_parent_category' );

        $this->browser
            ->open('/admin/contents')
            ->click(l::css('#row-'.$content->_id.' a'))
            ->waitForPageToLoad(1000)
            ->click(l::css('[data-tab-of=content-relations]'))
            ->select(l::IdOrName('category_id'), $category->name)
            ->click(l::id('submit-attach-category'))
            ->waitForPageToLoad(1000);

        $this->assertElementHasText(l::id('content-relations-table'), (string)$category->name);

        $content = Content::first($content->_id);
        $this->assertContains((string)$category->_id, $content->categories);
    }

    public function testShouldTagProductsToImage()
    {
        $attributes = testContentProvider::attributesFor( 'valid_image' );
        $product = testProductProvider::saved( 'simple_valid_product' );

        $imageFile = 'file://'.__DIR__.'/../assets/image.jpg';

        $this->browser
            ->open('/admin/contents')
            ->click(l::id('btn-create-new-content'))
            ->click(l::id('btn-create-new-image'))
            ->waitForPageToLoad(1000)
            ->type(l::IdOrName('name'), $attributes['name'])
            ->type(l::IdOrName('slug'), $attributes['slug'])
            ->attachFile(l::IdOrName('image_file'), $imageFile)
            ->click(l::id('submit-form'))
            ->open('/admin/contents')
            ->waitForPageToLoad(1000);

        $content = ImageContent::first(['slug'=>$attributes['slug']]);

        $content->attachToProducts($product);
        $content->save();

        $this->browser
            ->open(URL::action('Admin\ContentsController@edit', ['id'=>$content->_id]))
            ->click(l::css('[data-tab-of=content-image-tagging]'))
            ->runScript("$('span.tagged-image').trigger('click');")
            ->select( l::IdOrName('product_id'), $product->_id.' - '.$product->name )
            ->submit( l::css('.popover-tagging form') )
            ->waitForPageToLoad(2000);

        $this->assertLocation( URL::action('Admin\ContentsController@edit', ['id'=>$content->_id, 'tab'=>'content-image-tagging']) );

        $this->browser
            // Hover in the tag
            ->runScript("$('[data-tag-for-popover]').trigger('mouseover');") 
            // The .tagged-product-popover SHOULD HAVE the visible
            // class by now (when hovering in the tag).
            // An error will occur if the element is not found
            ->click(l::css('.tagged-product-popover.visible'));
    }
}
