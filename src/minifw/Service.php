<?php
namespace minifw;
class Service{
    private static $_instance;

    private $_services = [];

    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new Service();
        }
        return self::$_instance;
    }

    public static function set($name,$object){
        $my = self::getInstance();
        $my->_services[$name] = $object;
    }

    public static function get($name){
        $my = self::getInstance();
        if(@$my->_services[$name]){
            $ret =  $my->_services[$name];
            if(is_callable($ret)){
                $_ret = call_user_func($ret);
                return $_ret;
            }
            else{
                return $ret;
            }
        }
        return null;
    }
}