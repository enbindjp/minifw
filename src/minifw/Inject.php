<?php
namespace minifw;
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Inject{
    public function __construct(
        public string $name
    ){}
}