<?php

namespace DDD\Order\OrderState;

interface AStatusInterface
{
    public function nextState();
    public function backState();
    public function getState();
}
