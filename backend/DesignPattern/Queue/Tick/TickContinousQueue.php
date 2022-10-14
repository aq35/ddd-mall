<?php

namespace DesignPattern\Queue\Tick;

class TickContinousQueue extends BaseTickQueue
{
    /**
     * Flush the callback queue.
     *
     * Invokes callbacks which were on the queue when tick() was called and newly added ones.
     */
    public function tick()
    {
        while (!$this->queue->isEmpty() && $this->queueModel->isRunning()) {
            $this->callback = $this->queue->dequeue();
            $callback = $this->callback; // without this proxy PHPStorm marks line as fatal error.
            $callback($this->queueModel);
        }
    }
}
