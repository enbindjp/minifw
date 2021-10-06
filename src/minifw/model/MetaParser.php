<?php
namespace minifw\model;

class MetaParser{

    private static $_instance;
    public $_cache = [];

    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new MetaParser();
        }
        return self::$_instance;
    }

    public static function parse($instance){
        $my = self::getInstance();
        return $my->_refrection($instance);
    }

    public static function bindValue(&$instance,&$value){
        $my = self::getInstance();
        $ret = $my->_refrection($instance);

        foreach($ret['props'] as $prop){
            $name = $prop->name;
            if(@isset($value[$name])){
                $prop->setValue($instance,$value[$name]);
            }
        }
    }

    private function _refrection($instance){
        $clz_name = get_class($instance);

        if(@isset($this->_cache[$clz_name])){
            return $this->_cache[$clz_name];
        }

        $ref = new \ReflectionClass($instance);
        $tables = $ref->getAttributes(Table::class);
        if(!$tables){
            throw new \Exception("not found table attribute");
        }
        $table = $tables[0]->newInstance();
        $fields = [];
        $primary = null;

        $props = $ref->getProperties();
        foreach($props as $prop){
            $field_attrs = $prop->getAttributes(Field::class);
            if($field_attrs){
                $field = $field_attrs[0]->newInstance();
                $name = $field->name;
                $prop->setAccessible(true);
                $fields[$name] = $field;
                if($field->primary){
                    $primary = array(
                        'name' => $field->name,
                        'type' => $field->type
                    );
                }
            }
        }

        $this->_cache[$clz_name] = array(
            'table' => $table->name,
            'props' => $props,
            'fields' => $fields,
            'primary' => $primary
        );

        return $this->_cache[$clz_name];
    }
}