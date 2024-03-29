<?php

namespace DesignPattern\QueueDesign\SplQueue;

// [PHP SplQueue] 拡張
class FiniteSplQueue extends PhpSplQueue
{
    /**
     * コールバック キューをフラッシュします。
     * Flush the callback queue.
     *
     * tick() が呼び出されたときにキューにあったコールバックと、新しく追加されたコールバックを呼び出します。
     * Invokes callbacks which were on the queue when tick() was called and newly added ones.
     *
     * tickとは、時間のごくわずかな隙間、須臾(しゅゆ)のことだろうか
     * Queueのコールバック(関数)をある分だけ実行します。
     * dequeue() は、queueが空だと、RuntimeExceptionを投げる
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
}
