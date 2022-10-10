<?php

namespace DDD\Handler;

use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;
use DesignPattern\Middleware\Conceptions\Handler;

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
