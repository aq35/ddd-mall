<?php

namespace DesignPattern\QueueDesign\QueueHasTimer;

use DesignPattern\QueueDesign\QueueHasTimer\QueueHasTimerInterface;
use DesignPattern\QueueDesign\SplQueue\SelectQueueInterface;
use DesignPattern\QueueDesign\QueueHasTimer\Timer\Timer;

// [QueueHasTimer]
class QueueHasTimer extends Timer implements QueueHasTimerInterface
{
    /**
     * @var SelectQueueInterface
     */
    protected $selectQueue;

    /**
     * @param SelectQueueInterface $selectQueue
     * @param float $interval
     * @param callable $callback
     * @param bool $periodic
     * @param mixed|null $data
     */
    public function __construct(SelectQueueInterface $selectQueue, float $interval, callable $callback, $periodic = false, $data = null)
    {
        if ($interval < self::MIN_INTERVAL) {
            $interval = self::MIN_INTERVAL;
        }

        $this->selectQueue = $selectQueue;
        $this->interval = (float) $interval;
        $this->callback = $callback;
        $this->periodic = (bool) $periodic;
        $this->data = $data;
    }

    public function __destruct()
    {
        unset($this->selectQueue);
        unset($this->interval);
        unset($this->callback);
        unset($this->periodic);
        unset($this->data);
    }

    // SelectQueueInterface
    public function getQueue(): SelectQueueInterface
    {
        return $this->selectQueue;
    }
    // SelectQueueInterface
    public function isActive()
    {
        return $this->selectQueue->isTimerActive($this);
    }
    // SelectQueueInterface
    public function cancel()
    {
        if (isset($this->selectQueue)) {
            $this->selectQueue->cancelTimer($this);
        }
    }
}
