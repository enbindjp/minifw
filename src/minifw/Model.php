<?php
namespace minifw;

use phpfw\model\MetaParser;

class Model{

    /**
     * プライマリキーを指定して、オブジェクトを取得する
     * @param $id
     * @return false|static
     */
    public static function get($id){
        $instance = new static;
        $ret = MetaParser::parse($instance);
        $sql = sprintf("SELECT * FROM %s WHERE %s = ? LIMIT 1",
            $ret['table'],
            $ret['primary']['name'],
        );
        $pdo = self::_pdo();
        $sth = $pdo->prepare($sql);
        $exec = $sth->execute(array($id));
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        if($row){
            MetaParser::bindValue($instance,$row);
            return $instance;
        }
        return false;
    }

    private static function _pdo(){
        $pdo = Service::get('pdo');
        return $pdo;
    }
}