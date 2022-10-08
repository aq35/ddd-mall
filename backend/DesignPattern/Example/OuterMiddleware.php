<?php

namespace DesignPattern\Example;

use DesignPattern\Middleware\Conceptions\Middleware;
use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;
use DesignPattern\Middleware\Conceptions\Handler;

final class OuterMiddleware implements Middleware
{
    public function process(Input $input, Handler $next): Output
    {
        // Input
        $input->middleware_process['input_outer_middleware'] = microtime();
        $response = $next->handle($input);
        // Output
        $response->middleware_process['output_outer_middleware'] = microtime();
        return $response; // ここで$input や Output を加工する
    }
}
