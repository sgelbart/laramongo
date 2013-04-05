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
        if( ! static::$instance )
            static::$instance = new static;

        return static::$instance;
    }

    public static function instance( $template )
    {
        if(method_exists($static::getInstance(), $template))
        {
            return f::instance( $this->model, static::getInstance()->$template() );    
        }

        throw new Exception("Model template for test does not exists", 666);
    }

    public static function saved( $template )
    {
        if(method_exists($static::getInstance(), $template))
        {
            return f::create( $this->model, static::getInstance()->$template() );
        }

        throw new Exception("Model template for test does not exists", 666);
    }

    public static function attributesFor( $template )
    {
        if(method_exists($static::getInstance(), $template))
        {
            return f::attributesFor( $this->model, static::getInstance()->$template() );
        }

        throw new Exception("Model template for test does not exists", 666);
    }
}
