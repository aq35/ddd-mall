<?php

namespace DesignPattern\QueueDesign\SplQueue;

// [PHP SplQueue] 拡張
class ContinousSplQueue extends PhpSplQueue
{
    /**
     * コールバック キューをフラッシュします。
     * Flush the callback queue.
     *
     * tick() が呼び出されたときにキューにあったコールバックと、新しく追加されたコールバックを呼び出します。
     * Invokes callbacks which were on the queue when tick() was called and newly added ones.
     *
     * Queueのコールバック(関数)をある分だけ実行します。
     * dequeue() は、queueが空だと、RuntimeExceptionを投げる
     */
    public function tick()
    {
        while (!$this->sqlQueue->isEmpty() && $this->queueModel->isRunning()) {
            $this->callback = $this->sqlQueue->dequeue(); // キューからノードをデキューする。先に入れられたデータから順に取り出す。
            $callback = $this->callback;
            $callback($this->queueModel);
        }
    }
}
