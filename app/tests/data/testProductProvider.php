<?php

class testProductProvider extends testObjectProvider
{
    public static $model = 'Product';

    protected function simple_valid_product()
    {
        return [
            '_id' => 8800001,
            'name' => 'Coisa Valida',
            'category' => testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de coisa valida',
        ];
    }

    protected function simple_invalid_product()
    {
        return [
            '_id' => 8800002,
            'name' => 'Coisa Inválida',
            // Nenhuma categoria
            'description' => 'Descrição de coisa invalida',
        ];
    }
}
