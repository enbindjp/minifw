<?php
namespace minifw;

abstract class Application{

    abstract public function initialize();

    public function start(){
        $_path = isset($_SERVER['PATH_INFO'])? $_SERVER['PATH_INFO'] : "/";
        $log = Logger::getInstance();
        //  全体の初期処理
        $this->initialize();

        $router = Router::getInstance();
        $url = parse_url($_path);

        $method = $_SERVER['REQUEST_METHOD'];
        //  該当のパスに一致するControllerを見つける
        $route = $router->findMatch($method,$url['path']);

        $dispatcher = new Dispatcher($route);
        $ret = $dispatcher->execute();
        if($ret) {
            $ret->send();
        }
    }
}
