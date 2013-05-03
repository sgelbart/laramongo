<?php namespace Laramongo\StoresProductsIntegration;

class PriceCalculator {

    protected $priceTable = '';
    protected $resultPrice = array();

    /**
     * Receives the store's array with the prices values for each store.
     *
     * @param array $prices [description]
     */
    public function addPriceTable(array $prices)
    {
        $this->priceTable = $prices;
    }

    public function runCalculation()
    {
        $choosedFromPrices = array();
        $choosedToPrices   = array();
        $choosedStores     = array();

        $prices = $this->priceTable;

        /**
         * Get the elected prices will use at TO mode
         */
        foreach ($prices as $price) {
            $choosedToPrices[] = $this->choosePriceValue($price);
        }

        /**
         * Running the mode and receive the return value.
         */
        $resultTo = $this->mode($choosedToPrices);

        $prices = array();

        /**
         * Stores for create the FROM mode
         */
        foreach ($this->priceTable as $storePrices) {
            foreach ($storePrices as $price) {
                if ( $price == $resultTo ) {
                    $prices[] = $storePrices;
                    break;
                }
            }
        }

        /**
         * Getting the prices at Stores selected
         */
        foreach ($prices as $price) {
            if ($price['promotional_price']) {
                unset($price['promotional_price']);
            }

            $choosedFromPrices[] = $this->choosePriceValue($price);
        }

        /**
         * Getting the FROM mode.
         */
        $resultFrom = $this->mode($choosedFromPrices);

        // setting the result of prices
        $this->resultPrice = array(
            'from_price' => (float)$resultFrom,
            'to_price' => (float)$resultTo
        );
    }

    /**
     * Returns the array with the following informations:
     * from_price
     * to_price
     *
     * @return array
     */
    public function getResultByRegion()
    {
        return $this->resultPrice;
    }

    /**
     * Returns the price value by priority being:
     *    promotional_price
     *    background_section_price
     *    recommended_retail_price
     *
     * @param  array  $prices
     * @return float the choosed price
     */
    private function choosePriceValue (array $prices) {
        if( isset($prices['promotional_price']) && $prices['promotional_price'] > 0 ) {
            $result =  $prices['promotional_price'];
        }
        elseif ( $prices['background_section_price'] > 0 ) {
            $result =  $prices['background_section_price'];

        } elseif ($prices['recommended_retail_price'] > 0 ) {
            $result =  $prices['recommended_retail_price'];
        }

        return number_format($result, 2);
    }

    /**
     * Returns the mode through array passed
     * @param  array  $prices
     * @return float the mode
     */
    private function mode(array $prices)
    {
        $values = array_count_values($prices);

        // ordering by occurences (ASC)
        asort($values);

        /**
         * Returning the second smallest value if doesn`t have a mode
         */
        if (end($values) == 1) {
            // Ordering by key which has the price value
            ksort($values);

            // Verify if has more than 1 element at array
            if (count($values) == 1) {
                return array_keys($values)[0];
            }
            else {
                return array_keys($values)[1];
            }
        }

        return key($values);
    }
}
