<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\AStatusInterface;
use DDD\Order\OrderState\A1State;

class OrderContext
{
    private AStatusInterface $interface;

    public function __construct()
    {

        $this->interface = A1State::getInstance();
    }

    public function nextState()
    {

        $this->interface = $this->interface->nextState();
    }

    public function backState()
    {
        $this->interface = $this->interface->backState();
    }

    public function getState()
    {
        return $this->interface->getState();
    }
}
