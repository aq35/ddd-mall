<?php

namespace App\Http\Controllers;

use DesignPattern\Middleware\Example\MiddlewareTest;
use DesignPattern\EventDispatcher\Example\EventDispatcherExample;
use DesignPattern\EventDispatcher\Event;
use DDD\Entity\User;


class TestController extends Controller
{

    // 入力と出力をミドルウェアで分割
    public function middlewareTest()
    {
        dd(MiddlewareTest::test());
    }

    public function eventDispatcherTest()
    {
        $this->callListenerByClosure();
        $this->callListenerByClass();
        Event::doDispatch('test_1_function');
        Event::doDispatch('test_2_class');
    }

    public function observerTest()
    {
        \DesignPattern\Observer\Example\ObserverTest::test();
    }

    // 存在しないメールアドレス,パスワードは、大文字、小文字の半角英字、数字、記号の最低8~32
    public function validateMiddleware()
    {
        $userEntity = User::register('nagarestarzxc@.com', 'Test12345');
        $userEntity->getUserId();
        $data = $userEntity->validate(); //　バリデーションはregisterの後に行う。
        dd($userEntity->getUserId()->getUserId(), $userEntity, $data);
    }

    // テスト:
    // public function validateMiddleware()
    // {
    //     $userEntity = User::register('nagarestarzxc@.com', 'test12345');
    //     $data = $userEntity->validate();
    //     dd($data);
    // }

    private function callListenerByClosure()
    {
        Event::addListener('test_1_function', function () {
            echo 'This is a closure function call.' . "\n";
        });
    }

    private function callListenerByClass()
    {
        $example = new EventDispatcherExample();
        Event::addListener('test_2_class', [$example, 'example1']);
    }
}
