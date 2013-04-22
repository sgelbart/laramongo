<?php

class testCharacteristicProvider extends testObjectProvider
{
    public static $model = 'Characteristic';

    protected function valid_numeric_characteristic()
    {
        return [
            'name' => 'Capacidade',
            'type' => 'int',
            'layout-pos' => 'peças',
        ];
    }

    protected function valid_decimal_characteristic()
    {
        return [
            'name' => 'Quantidade',
            'type' => 'float',
            'layout-pos' => 'litros',
        ];
    }

    protected function valid_string_characteristic()
    {
        return [
            'name' => 'Coleção',
            'type' => 'string',
        ];
    }

    protected function valid_option_characteristic()
    {
        return [
            'name' => 'Cor',
            'type' => 'option',
            'values' => ['Vermelho','Verde','Preto','Azul'],
        ];
    }
}
