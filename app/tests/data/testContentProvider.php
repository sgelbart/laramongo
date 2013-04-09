<?php

class testContentProvider extends testObjectProvider
{
    public static $model = 'Content';

    protected function valid_article()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000001' ),
            'name' => 'Materia interessante',
            'slug' => 'materia_interessante',
            'kind' => 'article',
            'article' => '<h1>A Very nice article</h1>',
        ];
    }

    protected function invalid_article()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000002' ),
            'name' => 'Materia sem conteúdo',
            'slug' => 'materia_sem_conteudo',
            'kind' => 'article',
            'article' => '',
        ];
    }

    protected function hidden_valid_article()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000003' ),
            'name' => 'Invisible article',
            'slug' => 'invisible_article',
            'kind' => 'article',
            'article' => '<h1>A Very invisible article</h1>',
            'hidden' => 1,
        ];
    }
}
