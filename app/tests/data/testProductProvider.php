<?php

class testProductProvider extends testObjectProvider
{
    public static $model = 'Product';

    protected function simple_valid_product()
    {
        return [
            '_id' => 8800001,
            'name' => 'Coisa Valida',
            'category' => (string)testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de coisa valida',
        ];
    }

    protected function simple_invalid_product()
    {
        return [
            '_id' => 8800002,
            'name' => 'Coisa Inválida',
            'category' => '',
            'description' => 'Descrição de coisa invalida',
        ];
    }

    protected function simple_deactivated_product()
    {
        return [
            '_id' => 8800003,
            'name' => 'Prod Desabilitado',
            'category' => (string)testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de um produto desativado',
            'deactivated' => 1,
        ];
    }

    protected function product_with_details()
    {
        return [
            '_id' => 8800004,
            'name' => 'Prod Detalhado',
            'category' => (string)testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de um produto com detalhes',
            'details' => [
                'capacidade' => 3,
                'cor' => 'Verde',
            ]
        ];
    }

    protected function another_valid_product()
    {
        return [
            '_id' => 8800005,
            'name' => 'Outra coisa valida',
            'category' => (string)testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de outra coisa valida',
        ];
    }
}
