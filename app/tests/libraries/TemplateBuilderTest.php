<?php

use Laramongo\TemplateBuilder\TemplateBuilder;
use Mockery as m;

class TemplateBuilderTest extends TestCase
{

    public function testShouldMake()
    {
        $templateBuilder = new TemplateBuilder;

        $viewMock = m::mock('ViewEnvironment');
        $viewMock->shouldReceive('make')
            ->with('templates.base.name.of.view', ['param'=>1])
            ->once()
            ->andReturn('Rendered view');

        App::instance('view', $viewMock);

        $result = $templateBuilder->make('name.of.view', ['param'=> 1]);
        $this->assertEquals('Rendered view', $result);
    }

    public function testShouldsetTemplateOfProductParams()
    {
        $product = testProductProvider::instance('simple_valid_product');
        $category = $product->category();

        $category->template = 'foo';
        $category->save();

        $templateBuilder = new TemplateBuilder;

        $templateBuilder->setTemplateFor(['product' => $product]);

        try {
            App::make('Template');
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertContains('Templates\Foo\TemplateBuilder', $result);
    }

    public function testShouldsetTemplateOfCategoryParams()
    {
        $category = testCategoryProvider::instance('valid_leaf_category');
        $category->template = 'foo';
        $category->save();

        $templateBuilder = new TemplateBuilder;

        $templateBuilder->setTemplateFor(['category' => $category]);

        try {
            App::make('Template');
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertContains('Templates\Foo\TemplateBuilder', $result);
    }

    public function testShouldUseDefaultTemplate()
    {
        $templateBuilder = new TemplateBuilder;

        $templateBuilder->setTemplateFor(['category' => 'bob', 'product' => 'foo']);

        try {
            App::make('Template');
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertContains('Templates\Base\TemplateBuilder', $result);
    }
}
