<?php
namespace minifw;

class Request{
    private static $_instance;

    private $_content_type = "";
    private $_header = [];
    private $_method = "";
    private $_input = [];

    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new Request();
            $method = $_SERVER['REQUEST_METHOD'];
            self::$_instance->_method = $method;

            if($method == 'GET'){
                self::$_instance->_input = $_GET;
            }
            else if($method == 'POST'){
                self::$_instance->_content_type = $_SERVER['CONTENT_TYPE'];

                if($_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded'){
                    self::$_instance->_input = $_POST;
                }
                // ここにContent-Type毎の処理を記述する
            }
        }
        return self::$_instance;
    }

    public static function get($name){
        $my = self::getInstance();
        return @$my->_input[$name];
    }
}