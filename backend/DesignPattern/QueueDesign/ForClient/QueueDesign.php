<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\Timer\TimerInterface;
use DesignPattern\QueueDesign\SplQueue\SelectQueueInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;

// [QueueDesign] 本体クラス
class QueueDesign implements SelectQueueInterface
{
    /**
     * @var SelectQueueInterface
     */
    protected $selectQueue;

    /**
     * @param SelectQueueInterface
     */
    public function __construct(SelectQueueInterface $selectQueue)
    {
        $this->selectQueue = $selectQueue;
    }

    public function __destruct()
    {
        // unset($this->selectQueue);
    }

    public function getModel()
    {
        return $this->selectQueue;
    }

    public function erase($all = false)
    {
        $this->selectQueue->erase($all);

        return $this;
    }

    // 機能
    public function export(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->export($queueExtended->getModel(), $all);

        return $this;
    }


    public function import(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->import($queueExtended->getModel(), $all);

        return $this;
    }


    public function swap(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->swap($queueExtended->getModel(), $all);

        return $this;
    }

    // ループが現在実行されているかどうかを確認します
    public function isRunning()
    {
        return $this->selectQueue->isRunning();
    }


    public function addReadStream($stream, callable $listener)
    {
        $this->selectQueue->addReadStream($stream, $listener);
    }


    public function addWriteStream($stream, callable $listener)
    {
        $this->selectQueue->addWriteStream($stream, $listener);
    }


    public function removeReadStream($stream)
    {
        $this->selectQueue->removeReadStream($stream);
    }


    public function removeWriteStream($stream)
    {
        $this->selectQueue->removeWriteStream($stream);
    }


    public function removeStream($stream)
    {
        $this->selectQueue->removeStream($stream);
    }


    public function addTimer($interval, callable $callback)
    {
        return $this->selectQueue->addTimer($interval, $callback);
    }


    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->selectQueue->addPeriodicTimer($interval, $callback);
    }


    public function cancelTimer(TimerInterface $timer)
    {
        $this->selectQueue->cancelTimer($timer);
    }


    public function isTimerActive(TimerInterface $timer)
    {
        return $this->selectQueue->isTimerActive($timer);
    }


    public function onStart(callable $listener)
    {
        $this->selectQueue->onStart($listener);
    }


    public function onStop(callable $listener)
    {
        $this->selectQueue->onStop($listener);
    }


    public function onTick(callable $listener)
    {
        $this->selectQueue->onAfterTick($listener);
    }


    public function onBeforeTick(callable $listener)
    {
        $this->selectQueue->onBeforeTick($listener);
    }

    /**
     * [キュー構造] Splqueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->selectQueue->onAfterTick($listener);
    }


    public function tick()
    {
        $this->selectQueue->tick();
    }


    public function start()
    {
        $this->selectQueue->start();
    }


    public function stop()
    {
        $this->selectQueue->stop();
    }


    public function setFlowController($flowController)
    {
        $this->selectQueue->setFlowController($flowController);
    }


    public function getFlowController()
    {
        return $this->selectQueue->getFlowController();
    }
}
