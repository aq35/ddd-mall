<?php

namespace App\Http\Controllers;

use DesignPattern\Middleware\Example\MiddlewareTest;
use DesignPattern\EventDispatcher\Example\EventDispatcherExample;
use DesignPattern\EventDispatcher\Event;
use DDD\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Uid\UuidV1;

class TestController extends Controller
{
    // 入力と出力をミドルウェアで分割
    public function middlewareTest()
    {
        dd(MiddlewareTest::test());
    }
}
