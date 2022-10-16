<?php

namespace DesignPattern\Event\ForClient;

use DesignPattern\Event\Contract\EventEmitterInterface;

use DesignPattern\Queue\QueueAwareInterface;
use DesignPattern\Queue\QueueFeatureInterface;
use DesignPattern\Queue\BaseQueue\QueueInterface;

use DesignPattern\Queue\ForClient\Queue;
use DesignPattern\Queue\ForClient\SelectQueue;


// 並列式SeriesEventEmitter
class AsyncEventEmitter extends SeriesEventEmitter implements EventEmitterInterface, QueueAwareInterface
{

    /**
     * @var QueueInterface|QueueFeatureInterface|null
     */
    protected $queue = null;

    public function __construct()
    {
        $this->setQueue(new Queue(new SelectQueue()));
    }

    /**
     * @see QueueAwareInterface::setQueue
     */
    public function setQueue(QueueInterface|QueueFeatureInterface|null $queue = null)
    {
        $this->queue = $queue;
    }

    /**
     * @see QueueAwareInterface::getQueue
     */
    public function getQueue(): QueueInterface|QueueFeatureInterface|null
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
