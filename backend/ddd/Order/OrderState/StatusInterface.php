<?php

namespace DDD\Order\OrderState;

interface StatusInterface
{
    public function toA2State();
    public function backA1State();
    public function getState();
}
