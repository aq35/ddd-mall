<?php

namespace DDD\Handler;

use DesignPattern\Middleware\Conceptions\Middleware;
use DesignPattern\Middleware\Conceptions\Handler;
use DesignPattern\Middleware\ChangeMiddlewareToHanlderForPipelineBuilder\MiddlewareHandler;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION | 説明
 * ---------------------------------------------------------------------------------------------------------------------
 * ### PipelineBuilder　
 * コアとなるHandlerに、MiddlewareをラッパーしたValidationHandlerを囲っていく。
 * ミドルウェアパターンがベースになっています。
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE | 使い方
 * ---------------------------------------------------------------------------------------------------------------------
 *
 *        $input = new Input();
 *        $pipeline = (new PipelineBuilder) // インスタンス化します。
 *        ->use(new OuterMiddleware()) // ミドルウェアをセットします。
 *        ->use(new InnerMiddleware()) // ミドルウェアをセットします。
 *        ->build(new ConcreteHandler()); // コアとなる処理をセットします。
 *        $output = $pipeline->handle($input);
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */
final class PipelineBuilder
{
    /**
     * @var Middleware[]
     */
    private $middlewares = [];

    // ミドルウェアを登録するメソッド
    public function use(Middleware $middleware): self
    {
        array_unshift($this->middlewares, $middleware);
        return $this;
    }

    public function build(Handler $handler): Handler
    {
        foreach ($this->middlewares as $middleware) {
            // ### MiddlewareHandler ミドルウェアをHandlerにする。MiddlewareHandlerは、処理の途中で、handle()を行う。
            $handler = new MiddlewareHandler($middleware, $handler);
        }
        return $handler;
    }
}
