<?php

namespace DesignPattern\Example;

use DesignPattern\Middleware\Conceptions\Middleware;
use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;
use DesignPattern\Middleware\Conceptions\Handler;

final class InnerMiddleware implements Middleware
{
    public function process(Input $input, Handler $next): Output
    {
        // Input
        $input->middleware_process['input_inner_middleware'] = microtime(); // ミドルウェアのプロセスを計測する
        // 中断する場合は、呼ばずにOutputをreturnする。
        if ($input->stop_process == 2) {
            $output = new Output();
            $output->middleware_process = $input->middleware_process;
            return new Output();
        }

        $response = $next->handle($input);
        // Output
        $response->middleware_process['output_inner_middleware'] = microtime(); // ミドルウェアのプロセスを計測する
        return $response; // ここで$input や Output を加工する
    }
}
