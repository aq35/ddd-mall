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
     * イベントリスナーを使いします。
     *
     * @param string  $name     イベント名
     * @param mixed   $func     Can be a function name, closure function or class.
     * @param integer $priority The execution priority.
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
}
