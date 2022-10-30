<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\StatusInterface;

abstract class BaseState implements StatusInterface
{
    public function toA1State()
    {
        return A1State::getInstance();
    }

    public function toA2State()
    {
        return A2State::getInstance();
    }

    public function toA3State()
    {
        return A3State::getInstance();
    }

    public function toB1State()
    {
        return B1State::getInstance();
    }

    public function toB2State()
    {
        return B2State::getInstance();
    }

    public function toB3State()
    {
        return B3State::getInstance();
    }

    public function toC1State()
    {
        return C1State::getInstance();
    }

    public function toC2State()
    {
        return C2State::getInstance();
    }

    public function toC3State()
    {
        return C3State::getInstance();
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
