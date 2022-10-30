<?php

namespace DDD\Order\OrderState\State;

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

    public function toA1State()
    {
        return self::$instance;
    }

    public function getState()
    {
        return self::$status;
    }
}
