<?php

namespace DesignPattern\Event\ForClient;

use DesignPattern\Event\Contract\EventEmitterInterface;
use DesignPattern\Event\Domain\EventEmitterMode;
use DesignPattern\Event\Domain\EventListener;

// 直列式EventEmitter
class SeriesEventEmitter implements EventEmitterInterface
{
    /**
     * @var int
     */
    protected $emitterBlocked = EventEmitterMode::EVENTS_FORWARD;

    /**
     * @var int[]
     */
    protected $eventPointers = [];

    /**
     * @var EventListener[][]
     */
    protected $eventListeners = [];

    /**
     * @var EventEmitterInterface[]
     */
    protected $forwardListeners = [];

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->emitterBlocked);
        unset($this->eventPointers);
        unset($this->eventListeners);
        unset($this->forwardListeners);
    }

    /**
     * @see EventEmitterInterface::setMode
     */
    public function setMode($emitterMode)
    {
        $this->emitterBlocked = $emitterMode;
    }

    /**
     * @see EventEmitterInterface::getMode
     */
    public function getMode()
    {
        return $this->emitterBlocked;
    }

    /**
     * @see EventEmitterInterface::on
     */
    public function on($event, callable $listener)
    {
        if (!isset($this->eventListeners[$event])) {
            $this->eventPointers[$event] = 0;
            $this->eventListeners[$event] = [];
        }

        $pointer = &$this->eventPointers[$event];
        $eventListener = new EventListener($this, $event, $listener, $this->attachOnListener($pointer, $event, $listener));

        $this->eventListeners[$event][$pointer++] = $eventListener;

        return $eventListener;
    }

    /**
     * @see EventEmitterInterface::once
     */
    public function once($event, callable $listener)
    {
        if (!isset($this->eventListeners[$event])) {
            $this->eventPointers[$event] = 0;
            $this->eventListeners[$event] = [];
        }

        $pointer = &$this->eventPointers[$event];
        $eventListener = new EventListener($this, $event, $listener, $this->attachOnceListener($pointer, $event, $listener));

        $this->eventListeners[$event][$pointer++] = $eventListener;

        return $eventListener;
    }

    /**
     * @see EventEmitterInterface::times
     */
    public function times($event, $limit, callable $listener)
    {
        if ($limit === 0) {
            return $this->on($event, $listener);
        }

        if (!isset($this->eventListeners[$event])) {
            $this->eventPointers[$event] = 0;
            $this->eventListeners[$event] = [];
        }

        $pointer = &$this->eventPointers[$event];
        $limit = $limit > 0 ? $limit : 1;
        $eventListener = new EventListener(
            $this,
            $event,
            $listener,
            $this->attachTimesListener($pointer, $event, $limit, $listener)
        );

        $this->eventListeners[$event][$pointer++] = $eventListener;

        return $eventListener;
    }

    /**
     * @see EventEmitterInterface::delay
     */
    public function delay($event, $ticks, callable $listener)
    {
        $counter = 0;
        return $this->on($event, function (...$args) use (&$counter, $event, $ticks, $listener) {
            if (++$counter >= $ticks) {
                $listener(...$args);
            }
        });
    }

    /**
     * @see EventEmitterInterface::delayOnce
     */
    public function delayOnce($event, $ticks, callable $listener)
    {
        $counter = 0;
        return $this->times($event, $ticks, function (...$args) use (&$counter, $event, $ticks, $listener) {
            if (++$counter >= $ticks) {
                $listener(...$args);
            }
        });
    }

    /**
     * @see EventEmitterInterface::delayTimes
     */
    public function delayTimes($event, $ticks, $limit, callable $listener)
    {
        $counter = 0;
        return $this->times($event, $ticks + $limit - 1, function (...$args) use (&$counter, $event, $ticks, $listener) {
            if (++$counter >= $ticks) {
                $listener(...$args);
            }
        });
    }

    /**
     * @see EventEmitterInterface::removeListener
     */
    public function removeListener($event, callable $listener)
    {
        if (isset($this->eventListeners[$event])) {
            if (null !== $index = $this->findListener($event, $listener)); {
                unset($this->eventListeners[$event][$index]);
            }
        }
    }

    /**
     * @see EventEmitterInterface::removeListeners
     */
    public function removeListeners($event)
    {
        unset($this->eventPointers[$event]);
        unset($this->eventListeners[$event]);
    }

    /**
     * @see EventEmitterInterface::flushListeners
     */
    public function flushListeners()
    {
        unset($this->eventPointers);
        unset($this->eventListeners);

        $this->eventPointers = [];
        $this->eventListeners = [];
    }

    /**
     * @see EventEmitterInterface::findListener
     */
    public function findListener($event, callable $listener)
    {
        $listeners = isset($this->eventListeners[$event]) ? $this->eventListeners[$event] : [];

        foreach ($listeners as $index => $eventListener) {
            if ($listener === $eventListener->getHandler()) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @see EventEmitterInterface::emit
     */
    public function emit($event, $arguments = [])
    {
        $listeners = isset($this->eventListeners[$event]) ? $this->eventListeners[$event] : [];

        if (($this->emitterBlocked & EventEmitterMode::EVENTS_DISCARD_INCOMING) !== EventEmitterMode::EVENTS_DISCARD_INCOMING) {
            foreach ($listeners as $eventListener) {
                $listener = isset($eventListener->listener) ? $eventListener->listener : function () {
                };
                $listener(...$arguments);
            }
        }

        if (($this->emitterBlocked & EventEmitterMode::EVENTS_DISCARD_OUTCOMING) !== EventEmitterMode::EVENTS_DISCARD_OUTCOMING) {
            foreach ($this->forwardListeners as $listener) {
                $listener->emit($event, $arguments);
            }
        }
    }

    /**
     * @see EventEmitterInterface::copyEvent
     */
    public function copyEvent(EventEmitterInterface $emitter, $event)
    {
        return $this->on($event, function () use ($emitter, $event) {
            $emitter->emit($event, func_get_args());
        });
    }

    /**
     * @see EventEmitterInterface::copyEvents
     */
    public function copyEvents(EventEmitterInterface $emitter, $events)
    {
        $handlers = [];
        $events = (array) $events;

        foreach ($events as $event) {
            $handlers[] = $this->copyEvent($emitter, $event);
        }

        return $handlers;
    }

    /**
     * @see EventEmitterInterface::forwardEvents
     */
    public function forwardEvents(EventEmitterInterface $emitter)
    {
        $this->forwardListeners[] = $emitter;

        return $emitter;
    }

    /**
     * @see EventEmitterInterface::discardEvents
     */
    public function discardEvents(EventEmitterInterface $emitter)
    {
        foreach ($this->forwardListeners as $index => $listener) {
            if ($listener === $emitter) {
                unset($this->forwardListeners[$index]);
            }
        }

        return $emitter;
    }

    /**
     * @param int $pointer
     * @param string $event
     * @param callable $listener
     * @return callable
     */
    protected function attachOnListener($pointer, $event, callable $listener)
    {
        return $listener;
    }

    /**
     * @param int $pointer
     * @param string $event
     * @param callable $listener
     * @return callable
     */
    protected function attachOnceListener($pointer, $event, callable $listener)
    {
        $emitter = $this;
        return function (...$args) use ($emitter, $listener, $event, $pointer) {
            unset($emitter->eventListeners[$event][$pointer]);

            return $listener(...$args);
        };
    }

    /**
     * @param int $pointer
     * @param string $event
     * @param int $limit
     * @param callable $listener
     * @return callable
     */
    protected function attachTimesListener($pointer, $event, $limit, callable $listener)
    {
        $emitter = $this;
        return function (...$args) use ($emitter, $listener, $event, $pointer, &$limit) {
            if (--$limit === 0) {
                unset($limit);
                unset($emitter->eventListeners[$event][$pointer]);
            }
            return $listener(...$args);
        };
    }

    /**
     * Destruct method.
     */
    private function destructEventEmitterTrait()
    {
        $this->emitterBlocked = EventEmitterMode::EVENTS_FORWARD;
        $this->eventPointers = [];
        $this->eventListeners = [];
        $this->forwardListeners = [];
    }

    // デバッグ用
    public function eventListenerForDebug()
    {
        foreach ($this->eventListeners as $eventListener) {
            foreach ($eventListener as $eL) {
                var_dump($eL->getEvent());
            }
        }
    }
}
