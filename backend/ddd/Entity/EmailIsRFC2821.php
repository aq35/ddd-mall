<?php

namespace DDD\Entity;

use DesignPattern\Middleware\Conceptions\Middleware;
use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;
use DesignPattern\Middleware\Conceptions\Handler;

// ### EmailIsRFC2821 UserValidatorのバリデーションルール
final class EmailIsRFC2821 implements Middleware
{
    public function process(Input $input, Handler $next): Output
    {
        // Input
        if (!filter_var($input->params['email'], FILTER_VALIDATE_EMAIL)) {
            $input->errors['email'] = "RFC 2821 に準拠したメールアドレスを指定してください。";
        }
        $response = $next->handle($input);
        // Output
        return $response; // ここで$input や Output を加工する
    }
}
