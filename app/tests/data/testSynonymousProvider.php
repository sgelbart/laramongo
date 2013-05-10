<?php

class testSynonymousProvider extends testObjectProvider
{
    public static $model = 'Synonymous';

    protected function simple_valid_sym()
    {
        return [
            '_id' =>  new MongoId( '4af9f23d8ead0e1d32000001' ),
            'word' => 'i-pod',
            'related_word' => ['ipod', 'pod']
        ];
    }
}
