<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class RunValidationWithAButtonTest extends AcceptanceTestCase
{
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
        $category = f::create('Category', ['kind'=>'leaf']);

        $sampleFile = 'file://'.__DIR__.'/../assets/lixeirasAlgumasErradas.csv';

        // Import file
        $this->browser
            ->open(URL::action('Admin\ProductsController@import'))
            ->select(l::IdOrName('category'), $category->name)
            ->attachFile(l::IdOrName('csv_file'), $sampleFile)
            ->click(l::id('submit-import-form'))
            ->waitForPageToLoad(1000);

        // Create new characteristics
        $category->characteristics = $this->characteristicsSet();
        $category->save();

        // Validate new rules created
        $this->browser
            ->open(URL::action('Admin\CategoriesController@edit', ['id'=>$category->_id]))
            ->click(l::linkContaining('Caracteristicas'))
            ->click(l::linkContaining('Validar Produtos'))
            ->waitForPageToLoad(3000);

        $expectedResult = [
            'Lixeira u la la',
            'Lixeira Erronea',
        ];

        $this->assertBodyHasText( $expectedResult );
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
