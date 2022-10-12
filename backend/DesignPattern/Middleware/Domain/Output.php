<?php

namespace DesignPattern\Middleware\Domain;

// 出力
class Output
{
    /** @var array<string, string> */
    public $params = [];

    /** @var array<string, string> */
    public array $errors = [];
}
