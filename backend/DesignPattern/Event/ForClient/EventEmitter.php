<?php

namespace DesignPattern\ForClient\ForClient;

use DesignPattern\Event\Domain\EventEmitterInterface;

use DesignPattern\Event\ForClient\AsyncEventEmitter;
use DesignPattern\Event\Domain\EventListener;
use DesignPattern\Event\ForClient\BaseEventEmitter;
use DesignPattern\Event\Loop\LoopInterface;

class EventEmitter implements EventEmitterInterface
{
    /**
     * @var EventEmitterInterface
     */
    protected $emitter;

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop = null)
    {
        if ($loop !== null) {
            $this->emitter = new AsyncEventEmitter($loop);
        } else {
            $this->emitter = new BaseEventEmitter();
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->emitter);
    }

    /**
     * @see EventEmitterInterface::setMode
     */
    public function setMode($emitterMode)
    {
        $this->emitter->setMode($emitterMode);
    }

    /**
     * @see EventEmitterInterface::getMode
     */
    public function getMode()
    {
        return $this->emitter->getMode();
    }

    /**
     * @see EventEmitterInterface::on
     */
    public function on($event, callable $listener)
    {
        $handler = $this->emitter->on($event, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::once
     */
    public function once($event, callable $listener)
    {
        $handler = $this->emitter->once($event, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::times
     */
    public function times($event, $limit, callable $listener)
    {
        $handler = $this->emitter->times($event, $limit, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::delay
     */
    public function delay($event, $ticks, callable $listener)
    {
        $handler = $this->emitter->delay($event, $ticks, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::delayOnce
     */
    public function delayOnce($event, $ticks, callable $listener)
    {
        $handler = $this->emitter->delayOnce($event, $ticks, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::delayTimes
     */
    public function delayTimes($event, $ticks, $limit, callable $listener)
    {
        $handler = $this->emitter->delayTimes($event, $ticks, $limit, $listener);

        return new EventListener($this, $handler->event, $handler->handler, $handler->listener);
    }

    /**
     * @see EventEmitterInterface::removeListener
     */
    public function removeListener($event, callable $listener)
    {
        if (isset($this->emitter)) {
            $this->emitter->removeListener($event, $listener);
        }
    }

    /**
     * @see EventEmitterInterface::removeListeners
     */
    public function removeListeners($event)
    {
        if (isset($this->emitter)) {
            $this->emitter->removeListeners($event);
        }
    }

    /**
     * @see EventEmitterInterface::flushListeners
     */
    public function flushListeners()
    {
        if (isset($this->emitter)) {
            $this->emitter->flushListeners();
        }
    }

    /**
     * @see EventEmitterInterface::findListener
     */
    public function findListener($event, callable $listener)
    {
        return $this->emitter->findListener($event, $listener);
    }

    /**
     * @see EventEmitterInterface::emit
     */
    public function emit($event, $arguments = [])
    {
        $this->emitter->emit($event, $arguments);
    }

    /**
     * @see EventEmitterInterface::copyEvent
     */
    public function copyEvent(EventEmitterInterface $emitter, $event)
    {
        return $this->emitter->copyEvent($emitter, $event);
    }

    /**
     * @see EventEmitterInterface::copyEvents
     */
    public function copyEvents(EventEmitterInterface $emitter, $events)
    {
        return $this->emitter->copyEvents($emitter, $events);
    }

    /**
     * @see EventEmitterInterface::forwardEvents
     */
    public function forwardEvents(EventEmitterInterface $emitter)
    {
        return $this->emitter->forwardEvents($emitter);
    }

    /**
     * @see EventEmitterInterface::discardEvents
     */
    public function discardEvents(EventEmitterInterface $emitter)
    {
        return $this->emitter->discardEvents($emitter);
    }
}
