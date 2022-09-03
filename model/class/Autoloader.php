<?php
class Autoloader
{

    private static function autoload($class_name)
    {
        require './model/class/' . $class_name . '.php';
    }

    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
}
