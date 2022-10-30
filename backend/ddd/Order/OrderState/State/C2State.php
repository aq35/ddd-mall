<?php

namespace DDD\Order\OrderState\State;

final class C2State extends BaseState
{
    private static $status = 'C2';

    protected static $instance = null;
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function toC2State()
    {
        return self::$status;
    }

    public function getState()
    {
        return self::$status;
    }
}
