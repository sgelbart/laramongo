<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ValidateProductsAndDisplayErrorsTest extends AcceptanceTestCase
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

    public function testShouldDisplayErrorsWhenImporting()
    {
        $category = $this->aCategoryWithRules();

        $sampleFile = 'file://'.__DIR__.'/../assets/lixeirasAlgumasErradas.xlsx';

        $this->browser
            ->open(URL::action('Admin\ProductsController@import'))
            ->select(l::IdOrName('category'), $category->name)
            ->attachFile(l::IdOrName('csv_file'), $sampleFile)
            ->click(l::id('submit-import-form'))
            ->waitForPageToLoad(1000);

        $expectedResult = [
            'Produtos com erro 3',
            'Lixeira u la la',
            'Lixeira Erronea',
            'Cor','Tampa','Diâmetro',
            'Lixeira sem LM',
        ];

        $this->assertBodyHasText( $expectedResult );
    }

    public function testShouldDisplayErrorsWhenUpdating()
    {
        $category = $this->aCategoryWithRules();

        $product = testProductProvider::instance('simple_valid_product');
        $product->category = $category->_id;
        $product->save();

        $this->browser
            ->open(URL::action('Admin\ProductsController@edit', ['id'=>$product->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->select(l::IdOrName('cor'), 'Cromado')
            ->type(l::IdOrName('capacidade'), 'Errado')
            ->select(l::IdOrName('pedal'), 'Sim')
            ->select(l::IdOrName('tampa'), 'Sim')
            ->type(l::IdOrName('altura'), '20')
            ->type(l::IdOrName('diametro'), 'Errado')
            ->click(l::id('submit-save-product-characteristics'))
            ->waitForPageToLoad(1000)
            ->click(l::linkContaining('Caracteristicas'));

        $this->assertBodyHasNotText( 'Sucesso','Errado' );
    }

    private function aCategoryWithRules()
    {
        $category = testCategoryProvider::saved('valid_lixeiras_category');

        $charac_array = [
            [
                'type' => 'option',
                'name' => 'Cor',
                'values' => ['Cromado','Branco','Bege','Cinza']
            ],
            [
                'type' => 'float',
                'name' => 'Capacidade',
                'layout-pos' => 'litros'
            ],
            [
                'type' => 'option',
                'name' => 'Pedal',
                'values' => ['Sim','Não']
            ],
            [
                'type' => 'option',
                'name' => 'Tampa',
                'values' => ['Sim','Não']
            ],
            [
                'type' => 'float',
                'name' => 'Altura',
                'layout-pos' => 'cm'
            ],
            [
                'type' => 'float',
                'name' => 'Diâmetro',
            ],
        ];

        $category->characteristics = $charac_array;
        $category->save();

        return $category;
    }
}
