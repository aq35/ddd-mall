<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\State\StatusInterface;
use DDD\Order\OrderState\State\A1State;
use DDD\Order\OrderState\State\A2State;
use DDD\Order\OrderState\State\A3State;
use DDD\Order\OrderState\State\B1State;
use DDD\Order\OrderState\State\B2State;
use DDD\Order\OrderState\State\B3State;
use DDD\Order\OrderState\State\C1State;
use DDD\Order\OrderState\State\C2State;
use DDD\Order\OrderState\State\C3State;
use DDD\Order\OrderState\State\BaseState;

class OrderContext
{
    private StatusInterface $interface;

    public static function a1()
    {
        $self = new OrderContext;
        $self->interface = A1State::getInstance();
        return $self;
    }

    public static function b1()
    {
        $self = new OrderContext;
        $self->interface = B1State::getInstance();
        return $self;
    }

    public static function c1()
    {
        $self = new OrderContext;
        $self->interface = C1State::getInstance();
        return $self;
    }

    // 実行
    public function toSuccessState()
    {
        if ($this->interface instanceof A1State) {
            $this->interface = $this->interface->toA2State();
        } else if ($this->interface instanceof B1State) {
            $this->interface = $this->interface->toB2State();
        } else if ($this->interface instanceof C1State) {
            $this->interface = $this->interface->toC2State();
        }
    }

    // 実行
    public function toA1State()
    {
        $this->interface = $this->interface->toA1State();
    }

    // 実行成功
    public function toA2State()
    {
        $this->interface = $this->interface->toA2State();
    }

    // 実行失敗
    public function toA3State()
    {
        $this->interface = $this->interface->toA3State();
    }

    // もう一回
    public function toB1State()
    {
        $this->interface = $this->interface->toB1State();
    }

    // もう一回-成功
    public function toB2State()
    {
        $this->interface = $this->interface->toB2State();
    }

    // もう一回-失敗
    public function toB3State()
    {
        $this->interface = $this->interface->toB3State();
    }

    // ロールバック対象
    public function toC1State()
    {
        $this->interface = $this->interface->toC1State();
    }

    // ロールバック対象-成功
    public function toC2State()
    {
        $this->interface = $this->interface->toC2State();
    }

    // ロールバック対象-失敗
    public function toC3State()
    {
        $this->interface = $this->interface->toC3State();
    }

    public function getState()
    {
        return $this->interface->getState();
    }
}
