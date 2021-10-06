<?php
namespace minifw;
require_once "Logger.php";
class Loader{

    /**
     *  自動ロードを開始する
     */
    public static function register($dir){
        spl_autoload_register(function($clzname) use($dir){
            $logger = Logger::getInstance();
            $pos = strpos($clzname, "\\");
            $ns = substr($clzname,0,$pos);
            $_dirname = $dir.DIRECTORY_SEPARATOR.$ns;
            if(file_exists($_dirname)){
                $filename = $dir.DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR,$clzname).".php";
                if(file_exists($filename)){
                    require_once($filename);
                }
                else{
                    throw new \RuntimeException("not found class ".$clzname. "- ".$filename);
                }
            }
        });
    }
}