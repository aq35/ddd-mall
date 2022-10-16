<?php

namespace DesignPattern\QueueDesign\SplQueue;

use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;
use SplQueue;

class BaseSplQueue
{
    /**
     * @var QueueFeatureInterface|QueueManagerInterface
     */
    protected $queueModel;

    /**
     * [キュー構造] SplQueue
     * @var SplQueue
     * https://www.php.net/manual/en/class.splqueue.php
     */
    protected $sqlQueue;

    /**
     * @var callable
     */
    private $callback;

    /**
     *
     * @param QueueFeatureInterface|QueueManagerInterface $queueModel
     */
    public function __construct(QueueFeatureInterface|QueueManagerInterface $queueModel)
    {
        $this->queueModel = $queueModel;
        $this->sqlQueue = new SplQueue(); // [キュー構造] SplQueue
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->queueModel);
        unset($this->sqlQueue);
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
        $this->sqlQueue->enqueue($listener);
    }

    /**
     * Flush the callback queue.
     *
     * Invokes as many callbacks as were on the queue when tick() was called.
     */
    public function tick()
    {
        $count = $this->sqlQueue->count();

        while ($count-- && $this->queueModel->isRunning()) {
            $this->callback = $this->sqlQueue->dequeue();
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
        return $this->sqlQueue->isEmpty();
    }
}
