<?php

namespace DesignPattern\Middleware\Conceptions;

use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;

#### ミドルウェア(概念)

// Middleware は、パイプラインを継続する場合、$next->process()を呼ぶ。
// 中断する場合は、呼ばずに Output を return する。
interface Middleware
{
    // Middlewareは、パイプラインを継続する場合、$next->process()を呼ぶ。中断する場合は、呼ばずにOutputをreturnする。
    public function process(Input $input, Handler $next): Output;
}

/*
実装例
public function process(Input $input, Handler $next): Output
{
    // Input
    Input系、処理をかく
    $response = $next->handle($input);
    // Output
    Input系、処理をかく
    return $response;
}


実装例 内部のHandler or MiddlewareHandlerではなく、上のMiddlewareHandlerへ返す方法
public function process(Input $input, Handler $next): Output
{
    // Input
    Input系、処理をかく

    if(判定){
        return (new Output());
    }

    $response = $next->handle($input);
    // Output
    Input系、処理をかく
    return $response;
}
*/
