<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class InLineFixForInvalidCharacteristicsTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'imports' );
    }

    public function testShouldFixInvalidProducts()
    {
        $category = f::create('Category', ['kind'=>'leaf']);

        $sampleFile = 'file://'.__DIR__.'/../assets/lixeirasAlgumasErradas.csv';

        // Import file
        $this->browser
            ->open(URL::action('Admin\ProductsController@import'))
            ->select(l::IdOrName('category'), $category->name)
            ->attachFile(l::IdOrName('csv_file'), $sampleFile)
            ->click(l::id('submit-import-form'))
            ->waitForPageToLoad(10000);

        // Create new characteristics
        $category->characteristics = $this->characteristicsSet();
        $category->save();

        // Validate new rules created
        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->click(l::linkContaining('Validar Produtos'))
            ->waitForPageToLoad(3000);

            // Fix first product
        $this->browser
            ->select(l::css('#row-66666666-fix select.error'), 'Cromado')
            ->click(l::css('#row-66666666-fix button'));

            // Fix second product
        $this->browser
            ->select(l::css('#row-77777777-fix select.error'), 'Sim')
            ->type(l::css('#row-77777777-fix input.error'), '20')
            ->click(l::css('#row-77777777-fix button'));

        sleep(1); // Wait for ajax =(
            
        $productA = Product::first('66666666');
        $this->assertEquals('Cromado', $productA->details['cor']);
        $this->assertNull($productA->state);

        $productB = Product::first('77777777');
        $this->assertEquals('Sim', $productB->details['tampa']);
        $this->assertEquals('20', $productB->details['diametro']);
        $this->assertNull($productB->state);
    }

    private function characteristicsSet()
    {
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

        return $charac_array;
    }
}
