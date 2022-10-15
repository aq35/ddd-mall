<?php

namespace DesignPattern\Queue\SplQueue;

class ContinousSplQueue extends BaseSplQueue
{
    /**
     * コールバック キューをフラッシュします。
     * Flush the callback queue.
     *
     * tick() が呼び出されたときにキューにあったコールバックと、新しく追加されたコールバックを呼び出します。
     * Invokes callbacks which were on the queue when tick() was called and newly added ones.
     *
     * SplQueue は、一点にまとめたい。
     *
     * tickとは、時間のごくわずかな隙間、須臾(しゅゆ)のことだろうか
     * Queueのコールバック(関数)をある分だけ実行します。
     * dequeue() は、queueが空だと、RuntimeExceptionを投げる
     */
    public function tick()
    {
        while (!$this->queue->isEmpty() && $this->queueModel->isRunning()) {
            $this->callback = $this->queue->dequeue(); // キューからノードをデキューする。先に入れられたデータから順に取り出す。
            $callback = $this->callback;
            $callback($this->queueModel);
        }
    }
}
