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
            'tags' => 'interessante, artigo, bom',
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
            'tags' => 'Interessante, artigo, ruim',
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
            'tags' => 'interessante, Artigo, invisivel',
        ];
    }

    protected function valid_video()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000004' ),
            'name' => 'Making Of 1a Mostra Casa Leroy Merlin',
            'slug' => 'makingof_1a_mostra_casa_leroy_merlin',
            'kind' => 'video',
            'youTubeId' => 'BNQFsKCuwAc',
            'tags' => 'interessante, video, mostra casa,',
        ];
    }
}
