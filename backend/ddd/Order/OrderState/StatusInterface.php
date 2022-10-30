<?php

namespace DDD\Order\OrderState;

interface StatusInterface
{
    public function toA1State();
    public function toA2State();
    public function toA3State();

    public function toB1State();
    public function toB2State();
    public function toB3State();

    public function toC1State();
    public function toC2State();
    public function toC3State();

    public function getState();
}
