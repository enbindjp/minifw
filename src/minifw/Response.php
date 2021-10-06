<?php
namespace minifw;

class Response{

    public static $TYPE_REDIRECT = "redirect";
    public static $TYPE_VIEW = "view";
    public static $TYPE_DATA = "data";

    private $type;
    private $code = 200;
    protected $data = "";
    protected $view;


    public function __construct($type = null){
        if($type){
            $this->type = $type;
        }
        else{
            $this->type = self::$TYPE_DATA;
        }
    }
    /**
     * HTTPのステータスコードを設定する
     * @param $code
     */
    public function setStatusCode($code){
        $this->code = $code;
    }

    public function setContent($data){
        $this->data = $data;
    }

    public function send(){
        if($this->type == self::$TYPE_REDIRECT){
            header("Location: /index.php".$this->data);
        }
        else if($this->type == self::$TYPE_VIEW){
            $this->view->run();
        }
        else {
            print $this->data;
        }
    }

    public static function view($name,$variables = []){
        $response = new Response(self::$TYPE_VIEW);
        $view = new View($name,$variables);
        $response->view = $view;
        return $response;
    }

    public static function redirect($path){
        $response = new Response(self::$TYPE_REDIRECT);
        $response->data = $path;
        return $response;
    }
}
