<?php

namespace DesignPattern\queue\ForClient;

use DesignPattern\Queue\Timer\TimerInterface;
use DesignPattern\Queue\QueueExtendedInterface;
use DesignPattern\Queue\BaseQueue\QueueFeatureInterface;
use DesignPattern\Queue\Basequeue\QueueManagerInterface;


class Queue implements QueueExtendedInterface, QueueManagerInterface
{
    /**
     * @var QueueManagerInterface|QueueFeatureInterface
     */
    protected $queue;

    /**
     * @param QueueManagerInterface|QueueFeatureInterface
     */
    public function __construct(QueueManagerInterface|QueueFeatureInterface $queue)
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


    public function export(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->queue->export($queueExtended->getModel(), $all);

        return $this;
    }


    public function import(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->queue->import($queueExtended->getModel(), $all);

        return $this;
    }


    public function swap(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->queue->swap($queueExtended->getModel(), $all);

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
