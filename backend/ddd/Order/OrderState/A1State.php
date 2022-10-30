<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\AStatusInterface;

final class A1State implements AStatusInterface
{
    private static $status = 1;
    private static $instance = null;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new A1State();
        }
        return self::$instance;
    }

    public function nextState()
    {
        return A2State::getInstance();
    }

    public function backState()
    {
        return self::$instance;
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
