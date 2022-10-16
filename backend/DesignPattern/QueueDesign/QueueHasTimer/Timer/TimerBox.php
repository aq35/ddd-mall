<?php

namespace DesignPattern\QueueDesign\QueueHasTimer\Timer;


use SplObjectStorage; // [SplObjectStorage] ユニークなオブジェクトにする
use SplPriorityQueue; // [SplPriorityQueue] 優先順位つきキューの主要な機能を提供します。 最大ヒープを使用して実装しています。
use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerInterface;

class TimerBox
{
    /**
     * @var float
     */
    protected $time;

    /**
     * @var SplObjectStorage
     */
    protected $timers;

    /**
     * @var SplPriorityQueue
     */
    protected $scheduler;

    public function __construct()
    {
        $this->timers = new SplObjectStorage(); // [SplObjectStorage]
        $this->scheduler = new SplPriorityQueue(); // [SplPriorityQueue]
    }

    /**
     * @return float
     */
    public function updateTime()
    {
        return $this->time = microtime(true);
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time ?: $this->updateTime();
    }

    /**
     * @param TimerInterface $timer
     * @return bool
     */
    public function contains(TimerInterface $timer)
    {
        return $this->timers->contains(
            object: $timer
        ); // [SplObjectStorage] ストレージに特定のオブジェクトが含まれるかどうかを調べる
    }

    /**
     * @param TimerInterface $timer
     */
    public function add(TimerInterface $timer)
    {
        $interval = $timer->getInterval();
        $scheduledAt = $interval + $this->getTime();
        // [SplObjectStorage] オブジェクトをストレージに追加する
        $this->timers->attach(
            object: $timer,
            info: $scheduledAt
        );

        // [SplObjectStorage] キューに要素を挿入する
        // priority
        // 数字が大きいほど、優先される。
        // 時間は経てば経つほど大きい数字になる。
        // 今回、[scheduler]は、優先順位は、時間が早い方を優先したい。
        // priorityは、 -$scheduledAt をセットする。
        $this->scheduler->insert(
            value: $timer,
            priority: -$scheduledAt
        );
    }

    /**
     * @param TimerInterface $timer
     */
    public function remove(TimerInterface $timer)
    {
        $this->timers->detach($timer); // [SplObjectStorage] オブジェクトをストレージから取り除く
    }

    /**
     * 優先順位の高いTimerをスケジュールから取り出します。
     * @return null|bool|int|float|string
     */
    public function getFirst()
    {
        while ($this->scheduler->count()) {
            // 優先度が高いものから取り出す。
            $timer = $this->scheduler->top();

            if ($this->timers->contains($timer)) { // [SplObjectStorage] ストレージに特定のオブジェクトが含まれるかどうかを調べる
                return $this->timers[$timer];
            }

            // $timersに存在しない$timerは削除する。
            $this->scheduler->extract();
        }

        // schedulerには何もなかった。
        return null;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->timers) === 0;
    }

    public function tick()
    {
        $time = $this->updateTime(); // 時間を進める。
        $timers = $this->timers;
        $scheduler = $this->scheduler;

        while (!$scheduler->isEmpty()) {
            $timer = $scheduler->top();

            if (!isset($timers[$timer])) { // $timersに、$timerがない場合、$timerを削除していく。
                $scheduler->extract(); // $timerが削除される。
                $timers->detach($timer); // [SplObjectStorage] オブジェクトをストレージから取り除く
                continue;
            }

            if ($timers[$timer] >= $time) { // $timeより、$timerの中身が大きい場合、実行するものがない
                break;
            }

            $scheduler->extract(); // スケジュールの中身を削除する。

            $callback = $timer->getCallback();
            $callback($timer);

            if ($timer->isPeriodic() && isset($timers[$timer])) {
                $timers[$timer] = $scheduledAt = $timer->getInterval() + $time;
                $scheduler->insert($timer, -$scheduledAt);
            } else {
                $timers->detach($timer); // [SplObjectStorage] オブジェクトをストレージから取り除く
            }
        }
    }
}
