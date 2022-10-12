<?php

namespace DesignPattern\Middleware\ForClient;

use DesignPattern\Middleware\Domain\Middleware;
use DesignPattern\Middleware\Domain\Handler;
use DesignPattern\Middleware\ForPipelineBuilder\MiddlewareHandler;

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

    // イメージ
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
            $handler = new MiddlewareHandler($middleware, $handler);
        }
        return $handler;
    }
}
