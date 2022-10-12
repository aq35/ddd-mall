<?php

namespace DesignPattern\Event\Loop\Tick;

use DesignPattern\Event\Loop\LoopModelInterface;
use SplQueue;

class TickFiniteQueue
{
    /**
     * @var LoopModelInterface
     */
    protected $loop;

    /**
     * [キュー構造] SplQueue
     * @var SplQueue
     * https://www.php.net/manual/en/class.splqueue.php
     */
    protected $queue;

    /**
     * @var callable
     */
    private $callback;

    /**
     *
     * @param LoopModelInterface $loop
     */
    public function __construct(LoopModelInterface $loop)
    {
        $this->loop = $loop;
        $this->queue = new SplQueue(); // [キュー構造] SplQueue
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->loop);
        unset($this->queue);
    }

    /**
     * [キュー構造] SplQueue 操作
     * イベント ループの将来のティックで呼び出されるコールバックを追加します。
     * Add a callback to be invoked on a future tick of the event loop.
     *
     * コールバックは、タイマーまたはストリーム イベントの前に、キューに入れられた順序で実行されることが保証されます。
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
     * Invokes as many callbacks as were on the queue when tick() was called.
     */
    public function tick()
    {
        $count = $this->queue->count();

        while ($count-- && $this->loop->isRunning()) {
            $this->callback = $this->queue->dequeue();
            $callback = $this->callback; // without this proxy PHPStorm marks line as fatal error.
            $callback($this->loop);
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
