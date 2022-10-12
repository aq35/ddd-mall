<?php

namespace DDD\Validator;

use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

// ### ValidationHandler ミドルウェアパターンのコア処理部分
final class ValidationHandler implements Handler
{
    public function handle(Input $input): Output
    {
        $output = new Output();
        $output->params = $input->params;
        $output->errors = $input->errors;
        return $output; // ここで中核的な処理を行う
    }
}
