<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerInterface;
use DesignPattern\QueueDesign\SplQueue\SelectQueueInterface;
use DesignPattern\QueueDesign\SplQueue\QueueFeatureInterface;

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

    // [機能]
    public function erase($all = false)
    {
        $this->selectQueue->erase($all);

        return $this;
    }

    // [機能]
    public function export(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->export($queueExtended->getModel(), $all);

        return $this;
    }

    // [機能]
    public function import(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->import($queueExtended->getModel(), $all);

        return $this;
    }

    // [機能]
    public function swap(QueueFeatureInterface $queueExtended, $all = false)
    {
        $this->selectQueue->swap($queueExtended->getModel(), $all);

        return $this;
    }

    // [ライフサイクル] ループが現在実行されているかどうかを確認します
    public function isRunning()
    {
        return $this->selectQueue->isRunning();
    }

    // [ストリーム]
    public function addReadStream($stream, callable $listener)
    {
        $this->selectQueue->addReadStream($stream, $listener);
    }

    // [ストリーム]
    public function addWriteStream($stream, callable $listener)
    {
        $this->selectQueue->addWriteStream($stream, $listener);
    }

    // [ストリーム]
    public function removeReadStream($stream)
    {
        $this->selectQueue->removeReadStream($stream);
    }

    // [ストリーム]
    public function removeWriteStream($stream)
    {
        $this->selectQueue->removeWriteStream($stream);
    }

    // [ストリーム]
    public function removeStream($stream)
    {
        $this->selectQueue->removeStream($stream);
    }

    // [タイマー] 時間
    public function cancelTimer(TimerInterface $timer)
    {
        $this->selectQueue->cancelTimer($timer);
    }

    // [ライフサイクル] 時間
    public function addTimer($interval, callable $callback)
    {
        return $this->selectQueue->addTimer($interval, $callback);
    }

    // [ライフサイクル] 時間
    // [ライフサイクル] 状態管理
    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->selectQueue->addPeriodicTimer($interval, $callback);
    }

    // [ライフサイクル] 状態管理
    public function isTimerActive(TimerInterface $timer)
    {
        return $this->selectQueue->isTimerActive($timer);
    }

    // [ライフサイクル] onStart
    public function onStart(callable $listener)
    {
        $this->selectQueue->onStart($listener);
    }

    // [ライフサイクル] onStop
    public function onStop(callable $listener)
    {
        $this->selectQueue->onStop($listener);
    }

    // [ライフサイクル] onTick
    public function onTick(callable $listener)
    {
        $this->selectQueue->onAfterTick($listener);
    }

    // [ライフサイクル] onBeforeTick
    public function onBeforeTick(callable $listener)
    {
        $this->selectQueue->onBeforeTick($listener);
    }

    // [ライフサイクル] onAfterTick
    public function onAfterTick(callable $listener)
    {
        $this->selectQueue->onAfterTick($listener);
    }

    // [機能]
    public function tick()
    {
        $this->selectQueue->tick();
    }

    // [機能]
    public function start()
    {
        $this->selectQueue->start();
    }

    // [機能]
    public function stop()
    {
        $this->selectQueue->stop();
    }

    // [フロー] スクリプトトランザクション 状態の管理
    public function setFlowController($flowController)
    {
        $this->selectQueue->setFlowController($flowController);
    }

    // [フロー] スクリプトトランザクション 状態の管理
    public function getFlowController()
    {
        return $this->selectQueue->getFlowController();
    }
}
