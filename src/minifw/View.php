<?php
namespace minifw;
class View{

    private $_name;
    private $_variables;

    public function __construct($name,$variables = []){
        $this->_name = $name;
        $this->_variables = $variables;
    }

    public function run(){
        $blade = Service::get("view");
        $data = $blade->run($this->_name,$this->_variables);
        echo $data;
    }
}