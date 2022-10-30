<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\BaseState;

final class A2State extends BaseState
{
    private static $status = 'A2';

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
        return self::$status;
    }

    public function backA1State()
    {
        return A1State::getInstance();
    }

    public function getState()
    {
        return self::$status;
    }
}
