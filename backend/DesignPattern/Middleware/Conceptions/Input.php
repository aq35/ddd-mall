<?php

namespace DesignPattern\Middleware\Conceptions;

// 入力
class Input
{
    /** @var array<string, string> */
    public $params = [];

    /** @var array<string, string> */
    public array $errors = [];
}
