<?php

class testConjugatedProductProvider extends testObjectProvider
{
    public static $model = 'ConjugatedProduct';

    protected function simple_conjugated_product()
    {
        return [
            '_id' => 'CJ9900001',
            'name' => 'Kit de coisas',
            'category' => testCategoryProvider::saved('valid_leaf_category')->_id,
            'description' => 'Descrição de coisa valida',
            'conjugated' => [
                testProductProvider::saved('simple_valid_product')->_id,
                testProductProvider::saved('simple_deactivated_product')->_id,
            ]
        ];
    }
}
