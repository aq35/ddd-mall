<?php

namespace App\Http\Controllers;

use DesignPattern\Middleware\Example\Index;
use DesignPattern\EventDispatcher\Example\EventDispatcherExample;
use DesignPattern\EventDispatcher\Event;

class TestMiddlewareController extends Controller
{

    public function index()
    {
        dd(Index::test());
    }

    public function eventDispatcher()
    {
        $this->callListenerByClosure();
        $this->callListenerByClass();
        Event::doDispatch('test_1_function');
        Event::doDispatch('test_2_class');
    }

    public function callListenerByClosure()
    {
        Event::addListener('test_1_function', function () {
            echo 'This is a closure function call.' . "\n";
        });
    }

    public function callListenerByClass()
    {
        $example = new EventDispatcherExample();

        Event::addListener('test_2_class', [$example, 'example1']);
    }
}
