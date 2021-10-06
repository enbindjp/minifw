<?php
namespace minifw;
class Logger{
    /**
     * @var static
     */
    private static $_logger;

    private $log_fp = null;

    public function __construct(){
        $this->log_fp = fopen("/tmp/app.log","a");
    }
    /**
     * @return Logger;
     */
    public static function getInstance(){
        if(!isset(self::$_logger)) {
            self::$_logger = new Logger();
        }
        return self::$_logger;
    }

    /**
     * デバッグ用のログ
     * @param $msg
     */
    public function debug($msg){
        $this->log("debug",$msg);
    }

    protected function log($level,$msg){
        $_msg = sprintf("[%s] - [%s]\r\n",$level,$msg);
        fwrite($this->log_fp,$_msg);
    }
}
