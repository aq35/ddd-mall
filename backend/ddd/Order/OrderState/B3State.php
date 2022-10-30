<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\BaseState;

final class B3State extends BaseState
{
    private static $status = 'B3';

    protected static $instance = null;
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function toB3State()
    {
        return self::$status;
    }

    public function getState()
    {
        return self::$status;
    }
}
