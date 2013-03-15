<?php namespace Traits;

trait ToSelect
{
    public static function toOptions( $query = array() )
    {
        $all = static::where( $query );
        $result = array();

        foreach ($all as $item) {
            $result[(string)$item->_id] = $item->name;
        }

        return $result;
    }
}
