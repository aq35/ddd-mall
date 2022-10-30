<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\StatusInterface;
use DDD\Order\OrderState\A1State;

class OrderContext
{
    private StatusInterface $interface;

    public function __construct()
    {

        $this->interface = A1State::getInstance();
    }

    public function toA2State()
    {
        $this->interface = $this->interface->toA2State();
    }

    public function backA1State()
    {
        $this->interface = $this->interface->backA1State();
    }

    public function getState()
    {
        return $this->interface->getState();
    }
}
