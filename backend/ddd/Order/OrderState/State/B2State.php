<?php

namespace DDD\Order\OrderState\State;

final class B2State extends BaseState
{
    private static $status = 'B2';

    protected static $instance = null;
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function toB2State()
    {
        return self::$status;
    }

    public function getState()
    {
        return self::$status;
    }
}
