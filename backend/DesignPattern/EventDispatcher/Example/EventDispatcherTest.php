<?php

namespace DesignPattern\EventDispatcher\Example;

use DesignPattern\EventDispatcher\Event;

class EventDispatcherTest extends \PHPUnit\Framework\TestCase
{
    public function callListenerByClosure()
    {
        Event::addListener('test_1', function () {
            dd('This is a closure function call.');
        });
    }

    public function callListenerByFunction()
    {
        // test_event_disptcher is in bootstrap.php
        Event::addListener('test_2', 'test_event_disptcher');
    }

    public function callListenerByClass()
    {
        $example = new EventDispatcherExample();

        Event::addListener('test_3', [$example, 'example1']);
    }

    public function testDispatcherByClosure()
    {
        $this->callListenerByClosure();

        $this->expectOutputString('This is a closure function call.');

        Event::doDispatch('test_1');
    }

    public function testDispatcherByFunction()
    {
        $this->callListenerByFunction();

        $this->expectOutputString('This is a function call.');

        Event::doDispatch('test_2');
    }

    public function testDispatcherByClass()
    {
        $this->callListenerByClass();

        $this->expectOutputString('This is a class call.');

        Event::doDispatch('test_3');
    }
}
