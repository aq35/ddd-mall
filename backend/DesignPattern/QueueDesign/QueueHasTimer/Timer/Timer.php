<?php

namespace DesignPattern\QueueDesign\QueueHasTimer\Timer;

use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerInterface;

class Timer implements TimerInterface
{
    /**
     * @var float
     */
    const MIN_INTERVAL = 1e-6;

    /**
     * @var float
     */
    protected $interval;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var bool
     */
    protected $periodic;

    /**
     * @var mixed|null
     */
    protected $data;

    /**
     * @param float $interval
     * @param callable $callback
     * @param bool $periodic
     * @param mixed|null $data
     */
    public function __construct(float $interval, callable $callback, $periodic = false, $data = null)
    {
        if ($interval < self::MIN_INTERVAL) {
            $interval = self::MIN_INTERVAL;
        }

        $this->interval = (float) $interval;
        $this->callback = $callback;
        $this->periodic = (bool) $periodic;
        $this->data = $data;
    }

    public function __destruct()
    {
        unset($this->interval);
        unset($this->callback);
        unset($this->periodic);
        unset($this->data);
    }

    public function getInterval(): float
    {
        return $this->interval;
    }


    public function getCallback()
    {
        return $this->callback;
    }


    public function getData()
    {
        return $this->data;
    }


    public function setData($data)
    {
        $this->data = $data;
    }


    public function isPeriodic()
    {
        return $this->periodic;
    }
}
