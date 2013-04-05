<?php

class testCategoryProvider extends testObjectProvider
{
    public static $model = 'Category';

    protected function valid_leaf_category()
    {
        return [
            '_id' => new MongoId( '4af9f23d8ead0e1d32000001' ),
            'name' => 'Ferramentas específicas',
            'parents' => [
                testCategoryProvider::saved('valid_parent_category')->_id 
            ],
            'description' => 'Ferramentas com finalidades específicas',
        ];
    }

    protected function valid_parent_category()
    {
        return [
            '_id' => new MongoId( '4af9f23d8ead0e1d32000002' ),
            'name' => 'Ferramentas',
            'parents' => [],
            'description' => 'Ferramentas em geral',
        ];
    }
}
