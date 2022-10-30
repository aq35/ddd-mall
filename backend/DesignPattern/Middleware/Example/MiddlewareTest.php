<?php

namespace DesignPattern\Middleware\Example;

use DesignPattern\Middleware\Context\PipelineBuilder;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Example\OuterMiddleware;
use DesignPattern\Middleware\Example\InnerMiddleware;
use DesignPattern\Middleware\Example\ConcreteHandler;

class MiddlewareTest
{
    public static function test()
    {
        $input = new Input();
        $input->middleware_process = [];
        $pipeline = (new PipelineBuilder)
            ->use(new OuterMiddleware()) // ミドルウェア登録 ミドルウェアHandlerになる
            ->use(new InnerMiddleware()) // ミドルウェア登録 ミドルウェアHandlerになる
            ->build(new ConcreteHandler()); // Handler登録
        $output = $pipeline->handle($input);
        dd($output);
    }
}
