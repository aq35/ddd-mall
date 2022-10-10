<?php

namespace DDD\Handler;

use DesignPattern\Middleware\Conceptions\Middleware;
use DesignPattern\Middleware\Conceptions\Handler;
use DesignPattern\Middleware\ChangeMiddlewareToHanlderForPipelineBuilder\MiddlewareHandler;

// ### PipelineBuilder ValidationHandlerの周りにミドルウェアを巻いていく。
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

    // PipelineBuilderのuse()とbuild()のイメージ
    /*
    // MiddlewareHandler外側
    function handle(){
        手続き処理
        MiddlewareHandler外側@handle()
        {
            手続き処理
            MiddlewareHandler中側@handle(){
                // MiddlewareHandlerC
                function process(){
                    手続きコード
                    return <コア>Handler</コア>->handle();
                }
            }
        }
    }
    */
    public function build(Handler $handler): Handler
    {
        foreach ($this->middlewares as $middleware) {
            // ### MiddlewareHandler ミドルウェアをHandlerにする。MiddlewareHandlerは、処理の途中で、handle()を行う。
            $handler = new MiddlewareHandler($middleware, $handler);
        }
        return $handler;
    }
}
