<?php

namespace DesignPattern\Queue\Timer;

use DesignPattern\Queue\BaseQueue\QueueInterface;
use DesignPattern\Queue\QueueModelInterface;

class Timer implements TimerInterface
{
    /**
     * @var float
     */
    const MIN_INTERVAL = 1e-6;

    /**
     * @var QueueModelInterface|QueueInterface
     */
    protected $Queue;

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
     * @param QueueModelInterface|QueueInterface $Queue
     * @param float $interval
     * @param callable $callback
     * @param bool $periodic
     * @param mixed|null $data
     */
    public function __construct(QueueModelInterface|QueueInterface $Queue, float $interval, callable $callback, $periodic = false, $data = null)
    {
        if ($interval < self::MIN_INTERVAL) {
            $interval = self::MIN_INTERVAL;
        }

        $this->Queue = $Queue;
        $this->interval = (float) $interval;
        $this->callback = $callback;
        $this->periodic = (bool) $periodic;
        $this->data = $data;
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->Queue);
        unset($this->interval);
        unset($this->callback);
        unset($this->periodic);
        unset($this->data);
    }


    public function getQueue()
    {
        return $this->Queue;
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


    public function isActive()
    {
        return $this->Queue->isTimerActive($this);
    }


    public function cancel()
    {
        if (isset($this->Queue)) {
            $this->Queue->cancelTimer($this);
        }
    }
}
