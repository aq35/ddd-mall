<?php

namespace DDD\Tests\OrderState;

interface AStatusInterface
{
    public function nextState();
    public function backStatus();
}
