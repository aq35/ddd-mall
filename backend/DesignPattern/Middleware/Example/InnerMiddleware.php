<?php

namespace DesignPattern\Middleware\Example;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

final class InnerMiddleware implements Middleware
{
    public function process(Input $input, Handler $next): Output
    {
        // Input
        $input->middleware_process['input_inner_middleware'] = microtime(); // ミドルウェアのプロセスを計測する
        $response = $next->handle($input);
        // Output
        $response->middleware_process['output_inner_middleware'] = microtime(); // ミドルウェアのプロセスを計測する
        return $response; // ここで$input や Output を加工する
    }
}
