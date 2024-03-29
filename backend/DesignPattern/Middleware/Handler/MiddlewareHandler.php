<?php

namespace DesignPattern\Middleware\Handler;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Handler;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;

// ### MiddlewareHandler
// パイプラインを形成するためには、Middleware と Handler は同一視できなければならない。
// そこで、Middleware のインターフェイスを Handler のインターフェイスに変換するアダプターを実装する。
// このクラスは、クライアントコードへ提供するものではなく PipelineBuilder で使うことを想定している。
final class MiddlewareHandler implements Handler
{
    /**
     * @var Middleware
     */
    private $middleware;

    /**
     * @var Handler
     */
    private $handler;

    public function __construct(Middleware $middleware, Handler $handler)
    {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    public function handle(Input $input): Output
    {
        return $this->middleware->process($input, $this->handler);
    }
}
