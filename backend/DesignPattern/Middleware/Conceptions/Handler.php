<?php

namespace DesignPattern\Middleware\Conceptions;

use DesignPattern\Middleware\Conceptions\Input;
use DesignPattern\Middleware\Conceptions\Output;

#### ハンドラ(概念)
interface Handler
{
    public function handle(Input $input): Output;
}
