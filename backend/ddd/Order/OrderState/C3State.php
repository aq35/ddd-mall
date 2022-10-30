<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\BaseState;

final class C3State extends BaseState
{
    private static $status = 'C3';

    protected static $instance = null;
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function toC3State()
    {
        return self::$status;
    }

    public function getState()
    {
        return self::$status;
    }
}
