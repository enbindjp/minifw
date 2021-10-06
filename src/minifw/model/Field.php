<?php
namespace minifw\model;
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Field{
    public function __construct(
        public string $name,
        public int $type,
        public bool $primary = false
    ){}
}