<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\Timer\TimerInterface;
use DesignPattern\QueueDesign\QueueExtendedInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;
use DesignPattern\QueueDesign\Basequeue\QueueManagerInterface;

class QueueDesign implements QueueExtendedInterface, QueueManagerInterface
{
    /**
     * @var QueueManagerInterface|QueueFeatureInterface
     */
    protected $myQueue;

    /**
     * @param QueueManagerInterface|QueueFeatureInterface
     */
    public function __construct(QueueManagerInterface|QueueFeatureInterface $myQueue)
    {
        $this->myQueue = $myQueue;
    }

    public function __destruct()
    {
        // unset($this->myQueue);
    }

    public function getModel()
    {
        return $this->myQueue;
    }


    public function erase($all = false)
    {
        $this->myQueue->erase($all);

        return $this;
    }


    public function export(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->myQueue->export($queueExtended->getModel(), $all);

        return $this;
    }


    public function import(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->myQueue->import($queueExtended->getModel(), $all);

        return $this;
    }


    public function swap(QueueExtendedInterface $queueExtended, $all = false)
    {
        $this->myQueue->swap($queueExtended->getModel(), $all);

        return $this;
    }


    public function isRunning()
    {
        return $this->myQueue->isRunning();
    }


    public function addReadStream($stream, callable $listener)
    {
        $this->myQueue->addReadStream($stream, $listener);
    }


    public function addWriteStream($stream, callable $listener)
    {
        $this->myQueue->addWriteStream($stream, $listener);
    }


    public function removeReadStream($stream)
    {
        $this->myQueue->removeReadStream($stream);
    }


    public function removeWriteStream($stream)
    {
        $this->myQueue->removeWriteStream($stream);
    }


    public function removeStream($stream)
    {
        $this->myQueue->removeStream($stream);
    }


    public function addTimer($interval, callable $callback)
    {
        return $this->myQueue->addTimer($interval, $callback);
    }


    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->myQueue->addPeriodicTimer($interval, $callback);
    }


    public function cancelTimer(TimerInterface $timer)
    {
        $this->myQueue->cancelTimer($timer);
    }


    public function isTimerActive(TimerInterface $timer)
    {
        return $this->myQueue->isTimerActive($timer);
    }


    public function onStart(callable $listener)
    {
        $this->myQueue->onStart($listener);
    }


    public function onStop(callable $listener)
    {
        $this->myQueue->onStop($listener);
    }


    public function onTick(callable $listener)
    {
        $this->myQueue->onAfterTick($listener);
    }


    public function onBeforeTick(callable $listener)
    {
        $this->myQueue->onBeforeTick($listener);
    }

    /**
     * [キュー構造] Splqueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->myQueue->onAfterTick($listener);
    }


    public function tick()
    {
        $this->myQueue->tick();
    }


    public function start()
    {
        $this->myQueue->start();
    }


    public function stop()
    {
        $this->myQueue->stop();
    }


    public function setFlowController($flowController)
    {
        $this->myQueue->setFlowController($flowController);
    }


    public function getFlowController()
    {
        return $this->myQueue->getFlowController();
    }
}
