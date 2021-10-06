<?php
namespace minifw;
use \RuntimeException;

class Dispatcher{

    private $_route;

    private $_controller;
    private $_action;

    public function __construct($route){
        $this->_route = $route;
        $route = $this->_route[0];
        if(!$route){
            throw new \Exception("Please set !! Router:notFound(controller,action)");
        }
        $this->_controller = $route['controller'];
        $this->_action = $route['action'];
    }

    /**
     * @return mixed
     */
    public function getController(){
        return $this->_controller;
    }

    public function getAction(){
        return $this->_action;
    }

    public function setController($controller){
        $this->_controller = $controller;
    }
    public function setAction($action){
        $this->_action = $action;
    }

    public function execute()
    {
        $log = Logger::getInstance();

        $instance = new $this->_controller;
        $ref = new \ReflectionClass($this->_controller);
        $this->setInjection($instance,$ref);

        if(method_exists($instance,"beforeDispatch")){
            $old_ctrl = $this->_controller;
            $instance->beforeDispatch($this);
            if($old_ctrl != $this->_controller){
                // 再度インスタンスを作成する
                $log->debug("controller changed ....".$this->_controller." <-- ".$old_ctrl);
                $instance = new $this->_controller;
                $ref = new \ReflectionClass($this->_controller);
                $this->setInjection($instance,$ref);
            }
        }

        if (!method_exists($instance, $this->_action)) {
            throw new RuntimeException("dispatch error: method not found [".$this->_action."]");
        }

        if(method_exists($instance,"initialize")) {
            //  初期化処理をする
            $instance->initialize();
        }

        $ref_method = $ref->getMethod($this->_action);
        $params = $ref_method->getParameters();

        $this->_route[1];
        $args = array();
        foreach ($params as $p) {
            $name = $p->getName();
            if (@isset($this->_route[1][$name])) {
                $args[] = $this->_route[1][$name];
            } else {
                $args[] = null;
            }
        }

        $response = new Response();
        try {
            $ret = call_user_func_array(array($instance, $this->_action), $args);
            if (is_string($ret)) {
                $response->setContent($ret);
                return $response;
            }
            else if($ret instanceof Response){
                return $ret;
            }
        }
        catch (\Exception $ex){
            $response->setStatusCode(500);
            $response->setContent($ex->getMessage());
            return $response;
        }
    }

    /**
     * Injection で指定されたオブジェクトを設定する
     * @param $instance
     * @param $refection
     * @throws \ReflectionException
     */
    protected function setInjection(&$instance,$refection){

        //  #[Inject]で指定されたプロパティに値を設定する
        $clz_props = $refection->getProperties();
        foreach($clz_props as $clz_prop){
            $injection = $clz_prop->getAttributes(Inject::class);
            if($injection){
                $inject_service = $injection[0]->newInstance();
                $inject_object = Service::get($inject_service->name);
                $clz_prop->setAccessible(true);
                $clz_prop->setValue($instance,$inject_object);
            }
        }
    }
}
