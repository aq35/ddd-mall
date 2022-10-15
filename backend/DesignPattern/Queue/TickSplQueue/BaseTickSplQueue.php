<?php

namespace DesignPattern\Queue\TickSplQueue;

use DesignPattern\Queue\QueueModelInterface;
use DesignPattern\Queue\BaseQueue\QueueInterface;
use SplQueue;

class BaseTickSplQueue
{
    /**
     * @var QueueModelInterface|QueueInterface
     */
    protected $queueModel;

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
     * @param QueueModelInterface|QueueInterface $queueModel
     */
    public function __construct(QueueModelInterface|QueueInterface $queueModel)
    {
        $this->queueModel = $queueModel;
        $this->queue = new SplQueue(); // [キュー構造] SplQueue
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->queueModel);
        unset($this->queue);
    }

    /**
     * [キュー構造] SplQueue 操作
     * イベント ループの将来のティックで呼び出されるコールバックを追加します。
     * Add a callback to be invoked on a future tick of the event Queue.
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

        while ($count-- && $this->queueModel->isRunning()) {
            $this->callback = $this->queue->dequeue();
            $callback = $this->callback; // without this proxy PHPStorm marks line as fatal error.
            $callback($this->queueModel);
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
