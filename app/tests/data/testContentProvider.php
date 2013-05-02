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
            'type' => 'article',
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
            'type' => 'article',
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
            'type' => 'article',
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
            'type' => 'video',
            'youTubeId' => 'BNQFsKCuwAc',
            'tags' => 'interessante, video, mostra casa,',
        ];
    }

    protected function valid_image()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000005' ),
            'name' => 'Chegou a Leroy Merlin BH Norte',
            'slug' => 'leroy_merlin_bh_norte',
            'type' => 'image',
            'image' => '32f1423d8ead0e1d38000005.jpg',
            'tags' => 'interessante, leroy, bh, belo horizonte',
        ];
    }

    protected function valid_shop()
    {
        return [
            '_id' => new MongoId( '32f1423d8ead0e1d38000006' ),
            'name' => 'Interlagos',
            'slug' => 'loja_interlagos',
            'adress' => 'Rua dos cravos, n 2012',
            'type' => 'shop',
            'cep' => '123124214',
            'phones' => '011 32323232',
            'description' => 'Segunda a sexta 24 horas',
            'tags' => array(),
        ];
    }
}
