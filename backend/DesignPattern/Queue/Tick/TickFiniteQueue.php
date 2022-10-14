<?php

namespace DesignPattern\Queue\Tick;

class TickFiniteQueue extends BaseTickQueue
{
    /**
     * Flush the callback queue.
     *
     * Invokes as many callbacks as were on the queue when tick() was called.
     */
    public function tick()
    {
        $count = $this->queue->count();

        while ($count-- && $this->queueModel->isRunning()) {
            $this->callback = $this->queue->dequeue();
            $callback = $this->callback; // without this proxy PHPStorm marks line as fatal error.
            $callback($this->queueModel);
        }
    }
}
