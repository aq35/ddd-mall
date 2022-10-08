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
