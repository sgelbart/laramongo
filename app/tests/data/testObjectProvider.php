<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

/**
* testObjectProvider has the purpose of creating
* objects for the purpose of testing
*
*/
abstract class testObjectProvider
{
    public static $instance;

    public static $model = 'NameOfModel';

    public static function getInstance()
    {
        return new static;
    }

    public static function instance( $template )
    {
        if(method_exists(static::getInstance(), $template))
        {
            return f::instance( static::$model, static::getInstance()->$template() );    
        }

        throw new Exception("Model template '$template' for '".static::$model."' test does not exists", 666);
    }

    public static function saved( $template )
    {
        if(method_exists(static::getInstance(), $template))
        {
            return f::create( static::$model, static::getInstance()->$template() );
        }

        throw new Exception("Model template '$template' for '".static::$model."' test does not exists", 666);
    }

    public static function attributesFor( $template )
    {
        if(method_exists(static::getInstance(), $template))
        {
            return f::attributesFor( static::$model, static::getInstance()->$template() );
        }

        throw new Exception("Model template '$template' for '".static::$model."' test does not exists", 666);
    }
}
