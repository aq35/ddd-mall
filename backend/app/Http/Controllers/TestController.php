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

    // ブラウザがバックグラウンドで開いていると初期化されない。
    public function cookieTest()
    {
        config([
            'session.lifetime' => 120,
            'session.expire_on_close' =>  true
        ]);
        $count = session('count');
        $count++;
        session(['count' =>  $count]);
        return view('welcome', [
            'count' => $count,
        ]);
    }

    // public function cookieTest()
    // {
    //     $count = \Cookie::get('count', 0);
    //     $count++;
    //     \Cookie::queue('count', $count, 0);
    //     return view('welcome', [
    //         'count' => $count,
    //     ]);
    // }
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
