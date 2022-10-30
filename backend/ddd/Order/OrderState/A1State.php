<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\BaseState;

final class A1State extends BaseState
{
    private static $status = 'A1';

    protected static $instance = null;
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function toA2State()
    {
        return A2State::getInstance();
    }

    public function backA1State()
    {
        return self::$instance;
    }

    public function getState()
    {
        return self::$status;
    }
}
