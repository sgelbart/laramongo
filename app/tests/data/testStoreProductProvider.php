<?php

class testProductProvider extends testObjectProvider
{
    public static $model = 'Laramongo\StoresProductsIntegration\StoreProduct';

    protected function productA_price()
    {
        return [
            "_id" => 8800001,
            "unit" => 'un',
            "pack" => 1,
            "stores" => [
                "contagem" => [
                    "top" => 1,
                    "background_section" => 9.9,
                    "recommended_retail_price" => 9.01
                ]

            ]

        ];
    }

    protected function productB_price()
    {
        return [
            "_id" => 8800002,
            "unit" => 'un',
            "pack" => 1,
            "stores" => [
                "contagem" => [
                    "top" => 1,
                    "background_section" => 11.9,
                    "recommended_retail_price" => 11
                ]

            ]

        ];
    }

    protected function productC_price()
    {
        return [
            "_id" => 8800003,
            "unit" => 'un',
            "pack" => 1,
            "stores" => [
                "contagem" => [
                    "top" => 1,
                    "background_section" => 11.98,
                    "recommended_retail_price" => 11.98
                ]

            ]

        ];
    }
}
