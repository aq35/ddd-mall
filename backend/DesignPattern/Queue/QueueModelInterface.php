<?php

namespace DesignPattern\Queue;

use Dazzle\Queue\Flow\FlowController;
use DesignPattern\Queue\Timer\TimerInterface;

interface QueueModelInterface
{
    /**
     * Check if Queue is currently running.
     *
     * @return bool
     */
    public function isRunning();

    /**
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
     * Remove the write event listener for the given stream.
     *
     * @param resource $stream
     */
    public function removeWriteStream($stream);

    /**
     * Remove all listeners for the given stream.
     *
     * @param resource $stream
     */
    public function removeStream($stream);

    /**
     * Enqueue a callback to be invoked once after the given interval.
     *
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addTimer($interval, callable $callback);

    /**
     * Enqueue a callback to be invoked repeatedly after the given interval.
     *
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addPeriodicTimer($interval, callable $callback);

    /**
     * Cancel a pending timer.
     *
     * @param TimerInterface $timer
     */
    public function cancelTimer(TimerInterface $timer);

    /**
     * Check if a given timer is active.
     *
     * @param TimerInterface $timer
     * @return bool
     */
    public function isTimerActive(TimerInterface $timer);

    /**
     * Schedule a callback to be invoked on the start tick of event Queue.
     *
     * Callbacks are guarenteed to be executed in the order they are enqueued, before anything else.
     *
     * @param callable $listener
     */
    public function onStart(callable $listener);

    /**
     * Schedule a callback to be invoked on the stop tick of event Queue.
     *
     * Callbacks are guarenteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onStop(callable $listener);

    /**
     * Schedule a callback to be invoked on the next tick of the event Queue.
     *
     * Callbacks are guaranteed to be executed in the order they are enqueued, before any timer or stream events.
     *
     * @param callable $listener
     */
    public function onBeforeTick(callable $listener);

    /**
     * イベント ループの将来のティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on a future tick of the event Queue.
     *
     * コールバックは、エンキューされた順序で実行されることが保証されています。
     * Callbacks are guaranteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onAfterTick(callable $listener);

    /**
     * Perform a single iteration of the event Queue.
     */
    public function tick();

    /**
     * Run the event Queue until there are no more tasks to perform.
     */
    public function start();

    /**
     * Instruct a running event Queue to stop.
     */
    public function stop();

    /**
     * Set FlowController used by model.
     *
     * @param mixed $flowController
     */
    public function setFlowController($flowController);

    /**
     * Return FlowController used by model.
     *
     * @return FlowController
     */
    public function getFlowController();

    /**
     * Flush Queue.
     *
     * @param bool $all
     * @return QueueModelInterface
     */
    public function erase($all = false);

    /**
     * Export Queue not fired handlers and/or streams to another Queue model.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function export(QueueModelInterface $Queue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function import(QueueModelInterface $Queue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function swap(QueueModelInterface $Queue, $all = false);
}
