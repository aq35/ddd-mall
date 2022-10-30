<?php

namespace DesignPattern\Middleware\Context;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Handler;
use DesignPattern\Middleware\Handler\MiddlewareHandler;

// ### PipelineBuilder
// クライアントコードが Middleware パターンを扱いやすくするための補助的なクラス
// ミドルウェアとハンドラを結合しパイプラインを形成するコードを宣言的に書けるようにしてくれるクラス
//
// [ミドルウェアを登録するメソッド]　public function use(Middleware $middleware): self
//
// [登録されたミドルウェア]と[ハンドラを合成したハンドラ]を作るメソッド public function build(Handler $handler): Handler
//
// このクラスがあることでクライアントコードは次のように宣言的に書くことができる。
//
// $pipeline = (new PipelineBuilder)
//     ->use(new OuterMiddleware())
//     ->use(new InnerMiddleware())
//     ->build(new ConcreteHandler());
// $output = $pipeline->handle(new Input());
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
            $handler = new MiddlewareHandler($middleware, $handler);
        }
        return $handler;
    }
}
