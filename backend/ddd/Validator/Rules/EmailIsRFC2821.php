<?php

namespace DDD\Validator\Rules;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

// ### EmailIsRFC2821
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
