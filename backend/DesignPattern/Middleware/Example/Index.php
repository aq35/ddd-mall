<?php

namespace DesignPattern\Middleware\Example;

use DesignPattern\Middleware\HandleMiddlewareForClient\PipelineBuilder;
use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Example\OuterMiddleware;
use DesignPattern\Middleware\Example\InnerMiddleware;
use DesignPattern\Middleware\Example\ConcreteHandler;

class Index
{
    public static function test()
    {
        $input = new Input();
        $input->middleware_process = [];
        $pipeline = (new PipelineBuilder)
            ->use(new OuterMiddleware()) // ミドルウェア登録 ミドルウェアHandlerになる
            ->use(new InnerMiddleware()) // ミドルウェア登録 ミドルウェアHandlerになる
            ->build(new ConcreteHandler()); // Handler登録
        // 起動　ミドルウェアHandler::process の途中で　handle() → ミドルウェアHandler → Handler->Handle()
        // 起動　ミドルウェアHandler::process を再開 ← ミドルウェアHandler ← Handler->Handle()
        $output = $pipeline->handle($input);
        dd($output);
    }
}
