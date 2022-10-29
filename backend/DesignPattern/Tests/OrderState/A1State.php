<?php

namespace DDD\Tests\OrderState;

use DDD\Tests\OrderState\AStatusInterface;

final class A1State implements AStatusInterface
{
    private static $status = 2;
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

    public function backStatus()
    {
        return $this->status;
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
