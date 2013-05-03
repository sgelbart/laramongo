<?php namespace Laramongo\StoresProductsIntegration;

use Product;

/**
 * This class/model has the objective to represent the
 * price of a product and it's availability PER STORE
 * in order to calculate the price of the products in the
 * website per region.
 * 
 */
class StoreProduct extends \BaseModel {

    public static $factory = [];

    protected $collection = 'temp_storesProductsIntegration';

    /**
     * Calculate the price (from and to) of a region
     * 
     * @param  string $region Region slug. See the $regions attribute in the bottow of this class.
     * @return array          Array containing the base and the promotional price.
     */
    public function calculateRegionPrice( $region )
    {
        $priceTable = array();
        
        foreach ((array)array_get($this->regions, $region) as $store)
        {
            $store = array_get($this->stores, $store);

            if(! $store)
                continue;

            $priceTable[] = [
                'promotional_price' =>
                    array_get( $store, 'promotional_price'),
                'background_section_price' =>
                    array_get( $store, 'background_section'),
                'recommended_retail_price' => 
                    array_get( $store, 'recommended_retail_price')
            ];
        }

        $calculator = new PriceCalculator;
        $calculator->addPriceTable($priceTable);
        $calculator->runCalculation();

        return $calculator->getResultByRegion();
    }

    /**
     * Overwrites the save method in order to update
     * the actual price of the products
     * 
     * @param  boolean $force Force save, even with an invalid state
     * @return boolean        Success
     */
    public function save( $force = false )
    {
        if( parent::save( $force ) )
        {
            $product = Product::first($this->_id);
            if($product)
            {
                $priceArray = (array)$product->price;

                foreach ($this->regions as $name => $stores) {
                    $priceArray[$name] = $this->calculateRegionPrice( $name );
                }

                $product->price = $priceArray;

                $product->save();
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * The regions (praças) and the stores contained in each region
     * 
     * @var array
     */
    protected $regions = [
        'grande_sao_paulo' => [
            'raposo_tav',
            'tiete',
            'interlagos',
            's_caetano',
            'morumbi',
            'rjafet',
            'centernort',
        ],
        'rio_de_janeiro' => [
            'rio_norte',
            'rio_barra',
            'bangu',
            'niteroi',
            'jacarepagu',
        ],
        'compinas' => [
            'campinas',
            'anhanguera',
        ],
        'grande_porto_alegre' => [
            's_leopoldo',
            'poa',
        ],
        'rib_preto' => [
            'rib_preto',
        ],
        'sorocaba' => [
            'sorocaba',
        ],
        'curitiba' => [
            'curitiba',
            'curitibatu',
        ],
        'uberlandia' => [
            'uberlandia',
        ],
        'brasilia' => [
            'brasilia',
            'brasilia_n',
            'taguatinga',
        ],
        'sao_jose_dos_campos' => [
            'sjcampos',
        ],
        'sao_jose_do_rio_preto' => [
            'sj_r_preto',
        ],
        'goiana' => [
            'goiana',
        ],
        'belo_horizonte' => [
            'bh_norte',
            'bh_sul',
            'contagem',
        ],
        'londrina' => [
            'londrina',
        ]
    ];
}
