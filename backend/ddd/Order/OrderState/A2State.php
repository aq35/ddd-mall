<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\AStatusInterface;

final class A2State implements AStatusInterface
{
    private static $status = 2;
    private static $instance = null;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new A2State();
        }
        return self::$instance;
    }

    public function nextState()
    {
        return self::$status;
    }

    public function backState()
    {
        return A1State::getInstance();
    }

    public function getState()
    {
        return self::$status;
    }

    public final function __clone()
    {
        throw new \Exception('This Instance is Not Clone');
    }

    public final function __wakeup()
    {
        throw new \Exception('This Instance is Not unserialize');
    }
}
