<?php

namespace DesignPattern\Queue\BaseQueue;

use DesignPattern\Queue\Timer\TimerInterface;

interface QueueInterface
{
    /**
     * ループが現在実行されているかどうかを確認します
     * Check if Queue is currently running.
     *
     * @return bool
     */
    public function isRunning();

    /**
     * ストリームの読み取り準備が整ったときに通知を受けるリスナーを登録します
     * Register a listener to be notified when a stream is ready to read.
     *
     * @param resource $stream
     * @param callable $listener
     */
    public function addReadStream($stream, callable $listener);

    /**
     * Register a listener to be notified when a stream is ready to write.
     *
     * @param resource $stream
     * @param callable $listener
     */
    public function addWriteStream($stream, callable $listener);

    /**
     * Remove the read event listener for the given stream.
     *
     * @param resource $stream
     */
    public function removeReadStream($stream);

    /**
     * 指定されたストリームの書き込みイベント リスナーを削除します。
     * Remove the write event listener for the given stream.
     *
     * @param resource $stream
     */
    public function removeWriteStream($stream);

    /**
     * 指定されたストリームのすべてのリスナーを削除します。
     * Remove all listeners for the given stream.
     *
     * @param resource $stream
     */
    public function removeStream($stream);

    /**
     * 指定された間隔の後に 1 回呼び出されるコールバックをキューに入れます。
     * Enqueue a callback to be invoked once after the given interval.
     * 同時に実行するようにスケジュールされたタイマーの実行順序は保証されません。
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addTimer($interval, callable $callback);

    /**
     * 指定された間隔の後に繰り返し呼び出されるコールバックをキューに入れます。
     * Enqueue a callback to be invoked repeatedly after the given interval.
     * 同時に実行するようにスケジュールされたタイマーの実行順序は保証されません。
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addPeriodicTimer($interval, callable $callback);

    /**
     * 保留中のタイマーをキャンセルします。
     * Cancel a pending timer.
     *
     * @param TimerInterface $timer
     */
    public function cancelTimer(TimerInterface $timer);

    /**
     * 指定されたタイマーがアクティブかどうかを確認します。
     * Check if a given timer is active.
     *
     * @param TimerInterface $timer
     * @return bool
     */
    public function isTimerActive(TimerInterface $timer);

    /**
     * イベント ループの開始ティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the start tick of event Queue.
     * コールバックは、何よりも先に、キューに入れられた順序で実行されることが保証されています。
     * Callbacks are guarenteed to be executed in the order they are enqueued, before anything else.
     *
     * @param callable $listener
     */
    public function onStart(callable $listener);

    /**
     * イベント ループの停止ティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the stop tick of event Queue.
     * コールバックは、エンキューされた順序で実行されることが保証されています。
     * Callbacks are guarenteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onStop(callable $listener);

    /**
     * イベント ループの次のティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the next tick of the event Queue.
     * コールバックは、タイマーまたはストリーム イベントの前に、キューに入れられた順序で実行されることが保証されます。
     * Callbacks are guaranteed to be executed in the order they are enqueued, before any timer or stream events.
     *
     * @param callable $listener
     */
    public function onBeforeTick(callable $listener);

    /**
     * イベント ループの将来のティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on a future tick of the event Queue.
     * コールバックは、エンキューされた順序で実行されることが保証されています。
     * Callbacks are guaranteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onAfterTick(callable $listener);
}
