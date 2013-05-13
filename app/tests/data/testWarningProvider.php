<?php

class testWarningProvider extends testObjectProvider
{
    public static $model = 'Warning';

    protected function warning()
    {
        return [
            "_id" => 8800001,
            "keyword" => "Bacon",
            "last_search" => getdate()
        ];
    }
}
