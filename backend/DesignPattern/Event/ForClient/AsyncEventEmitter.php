<?php

namespace DesignPattern\Event\ForClient;

use DesignPattern\Event\Contract\EventEmitterInterface;

// [QueueDesign] が使えるようになる
use DesignPattern\QueueDesign\ForClient\QueueManagerForClientInterface;
use DesignPattern\QueueDesign\ForClient\QueueDesign;
use DesignPattern\QueueDesign\ForClient\SelectSplQueue;
use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;
use DesignPattern\QueueDesign\ForClient\SelectQueueInterface;

// 並列式EventEmitter
class AsyncEventEmitter extends SeriesEventEmitter implements EventEmitterInterface, QueueManagerForClientInterface
{
    /**
     * @var SelectQueueInterface|null
     */
    protected $queue = null;

    public function __construct()
    {
        // [QueueDesign] が使えるようになる
        $this->setQueue(new QueueDesign(new SelectSplQueue()));
    }

    /**
     * [QueueDesign]の状態管理
     * @see QueueAwareInterface::setQueue
     */
    public function setQueue(QueueManagerInterface|QueueFeatureInterface $queue = null)
    {
        $this->queue = $queue;
    }

    /**
     * [QueueDesign]の状態管理
     * @see QueueAwareInterface::getQueue
     */
    public function getQueue(): QueueManagerInterface|QueueFeatureInterface
    {
        return $this->queue;
    }

    protected function attachOnListener($pointer, $event, callable $listener)
    {
        return function () use ($listener) {
            $args = func_get_args();
            $this->getQueue()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }

    protected function attachOnceListener($pointer, $event, callable $listener)
    {
        return function () use ($listener, $event, $pointer) {
            unset($this->eventListeners[$event][$pointer]);

            $args = func_get_args();
            $this->getQueue()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }

    protected function attachTimesListener($pointer, $event, $limit, callable $listener)
    {
        $emitter = $this;
        return function () use ($emitter, $listener, $event, $pointer, &$limit) {
            if (--$limit === 0) {
                unset($limit);
                unset($emitter->eventListeners[$event][$pointer]);
            }

            $args = func_get_args();
            $this->getQueue()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }
}
