<?php

namespace DDD\Tests\OrderState;

use  DDD\Tests\OrderState\A1State;

final class OrderContext
{
    private AStatusInterface $state;

    public function __construct()
    {
        $this->state = A1State::getInstance();
    }

    public function changeState()
    {
        $this->state = $this->state->nextState();
    }

    public function backStatus()
    {
        return $this->state->backStatus();
    }
}
