<?php

namespace DesignPattern\Middleware\Domain;

use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;

#### ハンドラ(概念)
interface Handler
{
    public function handle(Input $input): Output;
}
