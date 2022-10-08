<?php

namespace DesignPattern\Example;

use DesignPattern\Middleware\HandleMiddlewareForClient\PipelineBuilder;
use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Example\OuterMiddleware;
use DesignPattern\Example\InnerMiddleware;
use DesignPattern\Example\ConcreteHandler;

class Index
{
    public static function test()
    {
        $input = new Input();
        $input->middleware_process = [];
        $pipeline = (new PipelineBuilder)
            ->use(new OuterMiddleware())
            ->use(new InnerMiddleware())
            ->build(new ConcreteHandler());
        $output = $pipeline->handle($input);
        dd($output);
    }
}
