<?php

namespace DesignPattern\Middleware\Example;

use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

final class ConcreteHandler implements Handler
{
    public function handle(Input $input): Output
    {
        $input->middleware_process['concreate_handler'] = microtime(); // ミドルウェアのプロセスを計測する
        $output = new Output();
        $output->middleware_process = $input->middleware_process;
        return $output; // ここで中核的な処理を行う
    }
}
