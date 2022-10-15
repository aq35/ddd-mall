<?php

namespace DesignPattern\Queue\ForClient;

use DesignPattern\Queue\BaseQueue\QueueInterface;
use DesignPattern\Queue\Tick\TickContinousSplQueue;
use DesignPattern\Queue\Tick\TickFiniteSplQueue;

use DesignPattern\Queue\Timer\Timer;
use DesignPattern\Queue\Timer\TimerBox;
use DesignPattern\Queue\Timer\TimerInterface;

use DesignPattern\Queue\FlowController;
use DesignPattern\Queue\QueueModelInterface;

class SelectQueue implements QueueModelInterface, QueueInterface
{
    /**
     * @var int
     */
    const MICROSECONDS_PER_SECOND = 1e6;

    /**
     * @var TickContinousSplQueue
     */
    protected $startTickQueue;

    /**
     * @var TickContinousSplQueue
     */
    protected $stopTickQueue;

    /**
     * @var TickContinousSplQueue
     */
    protected $nextTickQueue;

    /**
     * @var TickFiniteSplQueue
     */
    protected $futureTickQueue;

    /**
     * @var FlowController
     */
    protected $flowController; // 閉じたり(false)、開いたり(true)する。最初は開いていると[Queue]は利用できる

    /**
     * @var TimerBox
     */
    protected $timers;

    /**
     * @var resource[]
     */
    protected $readStreams = [];

    /**
     * @var callable[]
     */
    protected $readListeners = [];

    /**
     * @var resource[]
     */
    protected $writeStreams = [];

    /**
     * @var callable[]
     */
    protected $writeListeners = [];


    public function __construct()
    {
        $this->startTickQueue = new TickContinousSplQueue($this);
        $this->stopTickQueue = new TickContinousSplQueue($this);
        $this->nextTickQueue = new TickContinousSplQueue($this);
        $this->futureTickQueue = new TickFiniteSplQueue($this);
        $this->flowController = new FlowController();
        $this->timers = new TimerBox();
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->startTickQueue);
        unset($this->stopTickQueue);
        unset($this->nextTickQueue);
        unset($this->futureTickQueue);
        unset($this->flowController);
        unset($this->timers);
        unset($this->readStreams);
        unset($this->readListeners);
        unset($this->writeStreams);
        unset($this->writeListeners);
    }


    public function isRunning()
    {
        return isset($this->flowController->isRunning) ? $this->flowController->isRunning : false;
    }


    public function addReadStream($stream, callable $listener)
    {
        $key = (int) $stream;

        if (!isset($this->readStreams[$key])) {
            $this->readStreams[$key] = $stream;
            $this->readListeners[$key] = $listener;
        }
    }


    public function addWriteStream($stream, callable $listener)
    {
        $key = (int) $stream;

        if (!isset($this->writeStreams[$key])) {
            $this->writeStreams[$key] = $stream;
            $this->writeListeners[$key] = $listener;
        }
    }


    public function removeReadStream($stream)
    {
        $key = (int) $stream;

        unset(
            $this->readStreams[$key],
            $this->readListeners[$key]
        );
    }


    public function removeWriteStream($stream)
    {
        $key = (int) $stream;

        unset(
            $this->writeStreams[$key],
            $this->writeListeners[$key]
        );
    }


    public function removeStream($stream)
    {
        $this->removeReadStream($stream);
        $this->removeWriteStream($stream);
    }


    public function addTimer($interval, callable $callback)
    {
        $timer = new Timer($this, $interval, $callback, false);

        $this->timers->add($timer);

        return $timer;
    }


    public function addPeriodicTimer($interval, callable $callback)
    {
        $timer = new Timer($this, $interval, $callback, true);

        $this->timers->add($timer);

        return $timer;
    }


    public function cancelTimer(TimerInterface $timer)
    {
        if (isset($this->timers)) {
            $this->timers->remove($timer);
        }
    }


    public function isTimerActive(TimerInterface $timer)
    {
        return $this->timers->contains($timer);
    }


    public function onStart(callable $listener)
    {
        $this->startTickQueue->add($listener);
    }


    public function onStop(callable $listener)
    {
        $this->stopTickQueue->add($listener);
    }


    public function onBeforeTick(callable $listener)
    {
        $this->nextTickQueue->add($listener);
    }

    /**
     * [キュー構造] SplQueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->futureTickQueue->add($listener);
    }


    public function tick()
    {
        $this->flowController->isRunning = true;

        $this->nextTickQueue->tick();
        $this->futureTickQueue->tick();
        $this->timers->tick();
        $this->waitForStreamActivity(0);

        $this->flowController->isRunning = false;
    }


    // clientが使う。
    public function start()
    {
        // 他に作業しているので注意
        if ($this->flowController->isRunning) {
            return;
        }

        // TODO KRF-107
        $this->addPeriodicTimer(1, function () {
            usleep(1);
        });

        $this->flowController->isRunning = true;
        $this->startTickQueue->tick();

        while ($this->flowController->isRunning) {
            $this->nextTickQueue->tick();

            $this->futureTickQueue->tick();

            $this->timers->tick();

            // Next-tick or future-tick queues have pending callbacks ...
            if (!$this->flowController->isRunning || !$this->nextTickQueue->isEmpty() || !$this->futureTickQueue->isEmpty()) {
                $timeout = 0;
            }
            // There is a pending timer, only block until it is due ...
            else if ($scheduledAt = $this->timers->getFirst()) {
                $timeout = $scheduledAt - $this->timers->getTime();
                $timeout = ($timeout < 0) ? 0 : $timeout * self::MICROSECONDS_PER_SECOND;
            }
            // The only possible event is stream activity, so wait forever ...
            else if ($this->readStreams || $this->writeStreams) {
                $timeout = null;
            }
            // There's nothing left to do ...
            else {
                break;
            }

            $this->waitForStreamActivity($timeout);
        }
    }


    public function stop()
    {
        if (!$this->flowController->isRunning) {
            return;
        }

        $this->stopTickQueue->tick();
        $this->flowController->isRunning = false;
    }


    public function setFlowController($flowController)
    {
        $this->flowController = $flowController;
    }


    public function getFlowController()
    {
        return $this->flowController;
    }


    public function erase($all = false)
    {
        $this->stop();
        $queue = new static();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $this->$key = $queue->$key;
        }

        $this->flowController->isRunning = false;

        return $this;
    }


    public function export(QueueModelInterface $queue, $all = false)
    {
        $this->stop();
        $queue->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $queue->$key = $this->$key;
        }

        return $this;
    }


    public function import(QueueModelInterface $queue, $all = false)
    {
        $this->stop();
        $queue->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $this->$key = $queue->$key;
        }

        return $this;
    }


    public function swap(QueueModelInterface $queue, $all = false)
    {
        $this->stop();
        $queue->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $tmp = $queue->$key;
            $queue->$key = $this->$key;
            $this->$key = $tmp;
        }

        return $this;
    }

    /**
     * Wait/check for stream activity, or until the next timer is due.
     *
     * @param float $timeout
     */
    private function waitForStreamActivity($timeout)
    {
        $read  = $this->readStreams;
        $write = $this->writeStreams;

        if ($this->streamSelect($read, $write, $timeout) === false) {
            return;
        }

        foreach ($read as $stream) {
            $key = (int) $stream;

            if (isset($this->readListeners[$key])) {
                $callable = $this->readListeners[$key];
                $callable($stream, $this);
            }
        }

        foreach ($write as $stream) {
            $key = (int) $stream;

            if (isset($this->writeListeners[$key])) {
                $callable = $this->writeListeners[$key];
                $callable($stream, $this);
            }
        }
    }

    /**
     * Emulate a stream_select() implementation that does not break when passed empty stream arrays.
     *
     * @param array &$read
     * @param array &$write
     * @param integer|null $timeout
     *
     * @return integer The total number of streams that are ready for read/write.
     */
    private function streamSelect(array &$read, array &$write, $timeout)
    {
        if ($read || $write) {
            $except = null;

            return @stream_select($read, $write, $except, $timeout === null ? null : 0, $timeout);
        }

        usleep($timeout);

        return 0;
    }

    /**
     * Get list of properties that can be exported/imported safely.
     *
     * @return array
     */
    private function getTransferableProperties()
    {
        return [
            'nextTickQueue'     => null,
            'futureTickQueue'   => null,
            'flowController'    => null
        ];
    }

    public function onTick(callable $listener)
    {
        $this->queue->onAfterTick($listener);
    }
}
