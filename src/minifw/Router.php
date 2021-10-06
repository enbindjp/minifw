<?php
namespace minifw;

class Router{
    private static $_instance;
    private $routes = [];
    private $notFound = false;

    private static $_PTN_DELIM = "@";

    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    public static function get($path_pattern,$controller,$action){
        self::_any('GET',$path_pattern,$controller,$action);
    }

    public static function post($path_pattern,$controller,$action){
        self::_any('POST',$path_pattern,$controller,$action);
    }

    public static function _any($method,$path_pattern,$controller,$action){
        $my = self::getInstance();
        if(!isset($my->routes[$method])){
            $my->routes[$method] = [];
        }
        $my->routes[$method][] = array(
            'path' => $path_pattern,
            'controller' => $controller,
            'action' => $action
        );
    }

    public static function notFound($controller,$action){
        $my = self::getInstance();
        $my->notFound = array(
            'controller' => $controller,
            'action' => $action
        );
    }

    /**
     * 対象のPATHからルートを見つける
     * @param $method
     * @param $path
     */
    public function findMatch($method,$path){
        $log = Logger::getInstance();
        $log->debug(sprintf("findMatch [%s] -> [%s]",$method,$path));
        if(!isset($this->routes[$method])){
            return $this->notFound;
        }

        foreach($this->routes[$method] as $route){
            $ptn = sprintf("%s^%s$%s",self::$_PTN_DELIM,$route['path'],self::$_PTN_DELIM);
            preg_match($ptn,$path,$match);
            // $log->debug(sprintf("[%s] - pattern[%s] - [%s]",$path,$ptn,print_r($match,true)));

            if(count($match)){
                return array($route,$match);
            }
        }
        return array($this->notFound,[]);
    }
}
