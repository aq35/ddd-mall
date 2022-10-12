<?php

namespace DesignPattern\Event\ForClient;

use DesignPattern\Event\Loop\LoopAwareInterface;
use DesignPattern\Event\Loop\LoopInterface;

use DesignPattern\Event\Domain\EventEmitterInterface;

class AsyncEventEmitter extends BaseEventEmitter implements EventEmitterInterface, LoopAwareInterface
{

    /**
     * @var LoopInterface|null
     */
    protected $loop = null;

    /**
     * @see LoopAwareInterface::setLoop
     */
    public function setLoop(LoopInterface $loop = null)
    {
        $this->loop = $loop;
    }

    /**
     * @see LoopAwareInterface::getLoop
     */
    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @see BaseEventEmitterTrait::attachOnListener
     */
    protected function attachOnListener($pointer, $event, callable $listener)
    {
        return function () use ($listener) {
            $args = func_get_args();
            $this->getLoop()->onTick(function () use ($listener, $args) {
                $listener(...$args);
            });
        };
    }

    /**
     * @see BaseEventEmitterTrait::attachOnceListener
     */
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

    /**
     * @see BaseEventEmitterTrait::attachTimesListener
     */
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

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->setLoop($loop);
    }
}
