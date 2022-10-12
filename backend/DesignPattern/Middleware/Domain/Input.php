<?php

namespace DesignPattern\Middleware\Domain;

// 入力
class Input
{
    /** @var array<string, string> */
    public $params = [];

    /** @var array<string, string> */
    public array $errors = [];
}
