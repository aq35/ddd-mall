<?php

namespace DesignPattern\Queue\ForClient;

use DesignPattern\Queue\Timer\TimerInterface;
use DesignPattern\Queue\QueueExtendedInterface;
use DesignPattern\Queue\QueueModelInterface;

class Queue implements QueueExtendedInterface
{
    /**
     * @var QueueModelInterface
     */
    protected $Queue;

    /**
     * @param QueueModelInterface
     */
    public function __construct(QueueModelInterface $Queue)
    {
        $this->Queue = $Queue;
    }

    /**
     *
     */
    public function __destruct()
    {
        //        unset($this->Queue);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getModel()
    {
        return $this->Queue;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function erase($all = false)
    {
        $this->Queue->erase($all);

        return $this;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function export(QueueExtendedInterface $Queue, $all = false)
    {
        $this->Queue->export($Queue->getModel(), $all);

        return $this;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function import(QueueExtendedInterface $Queue, $all = false)
    {
        $this->Queue->import($Queue->getModel(), $all);

        return $this;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function swap(QueueExtendedInterface $Queue, $all = false)
    {
        $this->Queue->swap($Queue->getModel(), $all);

        return $this;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isRunning()
    {
        return $this->Queue->isRunning();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function addReadStream($stream, callable $listener)
    {
        $this->Queue->addReadStream($stream, $listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function addWriteStream($stream, callable $listener)
    {
        $this->Queue->addWriteStream($stream, $listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function removeReadStream($stream)
    {
        $this->Queue->removeReadStream($stream);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function removeWriteStream($stream)
    {
        $this->Queue->removeWriteStream($stream);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function removeStream($stream)
    {
        $this->Queue->removeStream($stream);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function addTimer($interval, callable $callback)
    {
        return $this->Queue->addTimer($interval, $callback);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->Queue->addPeriodicTimer($interval, $callback);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function cancelTimer(TimerInterface $timer)
    {
        $this->Queue->cancelTimer($timer);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isTimerActive(TimerInterface $timer)
    {
        return $this->Queue->isTimerActive($timer);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function onStart(callable $listener)
    {
        $this->Queue->onStart($listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function onStop(callable $listener)
    {
        $this->Queue->onStop($listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function onTick(callable $listener)
    {
        $this->Queue->onAfterTick($listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function onBeforeTick(callable $listener)
    {
        $this->Queue->onBeforeTick($listener);
    }

    /**
     * [キュー構造] SplQueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->Queue->onAfterTick($listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function tick()
    {
        $this->Queue->tick();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function start()
    {
        $this->Queue->start();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stop()
    {
        $this->Queue->stop();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setFlowController($flowController)
    {
        $this->Queue->setFlowController($flowController);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getFlowController()
    {
        return $this->Queue->getFlowController();
    }
}
