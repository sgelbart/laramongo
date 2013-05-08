<?php

class testStoreProductProvider extends testObjectProvider
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
                    "promotional_price" => 8.0,
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

    protected function productB_old_price()
    {
        return [
            "_id" => 8800002,
            "unit" => 'un',
            "pack" => 1,
            "stores" => [
                "contagem" => [
                    "top" => 1,
                    "promotional_price" => 7.0,
                    "background_section" => 19.9,
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

    protected function simple_valid_product_price()
    {
        return [
            "_id" => 8800001,
            "unit" => 'un',
            "pack" => 1,
            "stores" => [
                "raposo_tav" => [
                    "top" => 1,
                    "promotional_price" => 7.0,
                    "background_section" => 11.98,
                    "recommended_retail_price" => 11.98
                ],
                "tiete" => [
                    "top" => 2,
                    "background_section" => 11.98,
                    "recommended_retail_price" => 11.98
                ],
                "interlagos" => [
                    "top" => 1,
                    "promotional_price" => 7.0,
                    "background_section" => 10.98,
                    "recommended_retail_price" => 11.98
                ],
                "s_caetano" => [
                    "top" => 1,
                    "background_section" => 11.98,
                    "recommended_retail_price" => 11.98
                ],
                "morumbi" => [
                    "top" => 1,
                    "background_section" => 12.98,
                    "recommended_retail_price" => 11.98
                ],
                "rjafet" => [
                    "top" => 1,
                    "promotional_price" => 7.0,
                    "background_section" => 11.98,
                    "recommended_retail_price" => 11.98
                ],
                "centernort" => [
                    "top" => 1,
                    "background_section" => 9.9,
                    "recommended_retail_price" => 11.98
                ]
            ]
        ];
    }
}
