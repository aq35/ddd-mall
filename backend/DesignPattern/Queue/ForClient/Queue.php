<?php

namespace DesignPattern\queue\ForClient;

use DesignPattern\queue\Basequeue\QueueInterface;

use DesignPattern\queue\Timer\TimerInterface;
use DesignPattern\queue\queueExtendedInterface;
use DesignPattern\queue\queueModelInterface;

class queue implements queueExtendedInterface, QueueInterface
{
    /**
     * @var QueueInterface|queueModelInterface
     */
    protected $queue;

    /**
     * @param QueueInterface| queueModelInterface
     */
    public function __construct(QueueInterface|queueModelInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     *
     */
    public function __destruct()
    {
        //        unset($this->queue);
    }


    public function getModel()
    {
        return $this->queue;
    }


    public function erase($all = false)
    {
        $this->queue->erase($all);

        return $this;
    }


    public function export(queueExtendedInterface $queue, $all = false)
    {
        $this->queue->export($queue->getModel(), $all);

        return $this;
    }


    public function import(queueExtendedInterface $queue, $all = false)
    {
        $this->queue->import($queue->getModel(), $all);

        return $this;
    }


    public function swap(queueExtendedInterface $queue, $all = false)
    {
        $this->queue->swap($queue->getModel(), $all);

        return $this;
    }


    public function isRunning()
    {
        return $this->queue->isRunning();
    }


    public function addReadStream($stream, callable $listener)
    {
        $this->queue->addReadStream($stream, $listener);
    }


    public function addWriteStream($stream, callable $listener)
    {
        $this->queue->addWriteStream($stream, $listener);
    }


    public function removeReadStream($stream)
    {
        $this->queue->removeReadStream($stream);
    }


    public function removeWriteStream($stream)
    {
        $this->queue->removeWriteStream($stream);
    }


    public function removeStream($stream)
    {
        $this->queue->removeStream($stream);
    }


    public function addTimer($interval, callable $callback)
    {
        return $this->queue->addTimer($interval, $callback);
    }


    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->queue->addPeriodicTimer($interval, $callback);
    }


    public function cancelTimer(TimerInterface $timer)
    {
        $this->queue->cancelTimer($timer);
    }


    public function isTimerActive(TimerInterface $timer)
    {
        return $this->queue->isTimerActive($timer);
    }


    public function onStart(callable $listener)
    {
        $this->queue->onStart($listener);
    }


    public function onStop(callable $listener)
    {
        $this->queue->onStop($listener);
    }


    public function onTick(callable $listener)
    {
        $this->queue->onAfterTick($listener);
    }


    public function onBeforeTick(callable $listener)
    {
        $this->queue->onBeforeTick($listener);
    }

    /**
     * [キュー構造] Splqueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->queue->onAfterTick($listener);
    }


    public function tick()
    {
        $this->queue->tick();
    }


    public function start()
    {
        $this->queue->start();
    }


    public function stop()
    {
        $this->queue->stop();
    }


    public function setFlowController($flowController)
    {
        $this->queue->setFlowController($flowController);
    }


    public function getFlowController()
    {
        return $this->queue->getFlowController();
    }
}
