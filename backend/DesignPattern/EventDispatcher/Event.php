<?php

declare(strict_types=1);

namespace DesignPattern\EventDispatcher;

use DesignPattern\EventDispatcher\EventDispatcher;

/**
 * The helpers methods for Event Dispatcher.
 */

class Event
{
    /**
     * イベントリスナーを使います。
     *
     * @param string  $name  イベント名
     * @param mixed   $func  Can be a function name, closure function or class.
     * @param integer $priority 　The execution priority.
     *
     * @return void
     */
    public static function addListener(string $name, $func, int $priority = 10)
    {
        return EventDispatcher::instance()->addListener(
            $name,
            $func,
            $priority
        );
    }

    /**
     * Execute an event.
     *
     * @param string $name The name of an event.
     * @param array  $args The arguments.
     *
     * @return mixed
     */
    public static function doDispatch(string $name, array $args = [])
    {
        return EventDispatcher::instance()->doDispatch(
            $name,
            $args
        );
    }

    public static function event()
    {
        return EventDispatcher::instance();
    }
}

// 定義
// Event::addListener('test_1_function', function () {
//     echo 'This is a closure function call.' . "\n";
// });

// 利用
// Event::doDispatch('test_1_function');
// ------------------------------------------------------------

// 定義
// $example = new EventDispatcherExample();
// Event::addListener('test_2_class', [$example, 'example1']);

// 利用
// Event::doDispatch('test_2_function');
