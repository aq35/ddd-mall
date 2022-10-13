<?php

namespace DesignPattern\_Event\ForClient;

use DesignPattern\Event\Loop\ForClient\Loop;
use DesignPattern\Event\Loop\ForClient\SelectLoop;

use DesignPattern\_Event\Contract\EventEmitterInterface;
use DesignPattern\Event\Loop\LoopAwareInterface;
use DesignPattern\Event\Loop\LoopInterface;
use DesignPattern\Event\Loop\LoopModelInterface;

// 並列式SeriesEventEmitter
class AsyncEventEmitter extends SeriesEventEmitter implements EventEmitterInterface, LoopAwareInterface
{

    /**
     * @var LoopInterface|LoopModelInterface|null
     */
    protected $loop = null;

    /**
     * @see LoopAwareInterface::setLoop
     */
    public function setLoop(LoopInterface|LoopModelInterface|null $loop = null)
    {
        $this->loop = $loop;
    }

    /**
     * @see LoopAwareInterface::getLoop
     */
    public function getLoop(): LoopInterface|LoopModelInterface|null
    {
        return $this->loop;
    }

    protected function attachOnListener($pointer, $event, callable $listener)
    {
        return function () use ($listener) {
            $args = func_get_args();
            $this->getLoop()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }

    protected function attachOnceListener($pointer, $event, callable $listener)
    {
        return function () use ($listener, $event, $pointer) {
            unset($this->eventListeners[$event][$pointer]);

            $args = func_get_args();
            $this->getLoop()->onTick(function () use ($listener, $args) {
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
            $this->getLoop()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }

    public function __construct()
    {
        $this->setLoop(new Loop(new SelectLoop()));
    }
}
