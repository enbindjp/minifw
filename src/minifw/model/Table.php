<?php
namespace minifw\model;
#[\Attribute(\Attribute::TARGET_CLASS)]
class Table{
    public function __construct(
        public string $name
    ){}
}