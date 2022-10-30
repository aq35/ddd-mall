<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\StatusInterface;

abstract class BaseState implements StatusInterface
{

    public final function __clone()
    {
        throw new \Exception('This Instance is Not Clone');
    }

    public final function __wakeup()
    {
        throw new \Exception('This Instance is Not unserialize');
    }
}
