<?php

trait toSelect
{
    public static function allToOptions()
    {
        $all = static::all();
        $result = array();

        foreach ($all as $item) {
            $result[(string)$item->_id] = $item->name;
        }

        return $result;
    }
}
