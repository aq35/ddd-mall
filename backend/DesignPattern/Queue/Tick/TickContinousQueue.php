<?php

namespace DesignPattern\Queue\Tick;

use DesignPattern\Queue\QueueModelInterface;
use SplQueue;

class TickContinousQueue
{
    /**
     * @var QueueModelInterface
     */
    protected $Queue;

    /**
     * @var SplQueue
     */
    protected $queue;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @param QueueModelInterface $Queue
     */
    public function __construct(QueueModelInterface $Queue)
    {
        $this->Queue = $Queue;
        $this->queue = new SplQueue();
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->Queue);
        unset($this->queue);
    }

    /**
     * Add a callback to be invoked on the next tick of the event Queue.
     *
     * Callbacks are guaranteed to be executed in the order they are enqueued, before any timer or stream events.
     *
     * @param callable $listener
     */
    public function add(callable $listener)
    {
        $this->queue->enqueue($listener);
    }

    /**
     * Flush the callback queue.
     *
     * Invokes callbacks which were on the queue when tick() was called and newly added ones.
     */
    public function tick()
    {
        while (!$this->queue->isEmpty() && $this->Queue->isRunning()) {
            $this->callback = $this->queue->dequeue();
            $callback = $this->callback; // without this proxy PHPStorm marks line as fatal error.
            $callback($this->Queue);
        }
    }

    /**
     * Check if the next tick queue is empty.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->queue->isEmpty();
    }
}
