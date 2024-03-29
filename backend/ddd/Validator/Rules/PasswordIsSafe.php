<?php

namespace DDD\Validator\Rules;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

// ### PasswordIsSafe
final class PasswordIsSafe implements Middleware
{
    // 正規表現を用いて8文字以上アルファベットの大文字小文字、数字、記号を含める
    const VALID_PASSWORD_REGEX = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[\W_])[!-~]{8,}+\z/';

    public function process(Input $input, Handler $next): Output
    {
        // Input
        if (!isset($input->params['password']) || !preg_match(self::VALID_PASSWORD_REGEX, $input->params['password'])) {
            $input->errors['password'] = "パスワードは、8文字以上、アルファベットの大文字小文字、数字と記号を含めてください。";
        }
        $response = $next->handle($input);
        // Output
        return $response; // ここで$input や Output を加工する
    }
}
