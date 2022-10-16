<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;

use DesignPattern\QueueDesign\SplQueue\ContinousSplQueue;
use DesignPattern\QueueDesign\SplQueue\FiniteSplQueue;

use DesignPattern\QueueDesign\Timer\Timer;
use DesignPattern\QueueDesign\Timer\TimerBox;
use DesignPattern\QueueDesign\Timer\TimerInterface;

use DesignPattern\QueueDesign\FlowController;

use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;

class SelectQueue implements QueueFeatureInterface, QueueManagerInterface
{
    /**
     * @var int
     */
    const MICROSECONDS_PER_SECOND = 1e6;

    /**
     * @var ContinousSplQueue
     */
    protected $startSqlQueue;

    /**
     * @var ContinousSplQueue
     */
    protected $stopSplQueue;

    /**
     * @var ContinousSplQueue
     */
    protected $nextSqlQueue;

    /**
     * @var TickFiniteSplQueue
     */
    protected $futureSqlQueue;

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
        $this->startSqlQueue = new ContinousSplQueue($this);
        $this->stopSplQueue = new ContinousSplQueue($this);
        $this->nextSqlQueue = new ContinousSplQueue($this);
        $this->futureSqlQueue = new FiniteSplQueue($this);
        $this->flowController = new FlowController();
        $this->timers = new TimerBox();
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->startSqlQueue);
        unset($this->stopSplQueue);
        unset($this->nextSqlQueue);
        unset($this->futureSqlQueue);
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
        // add
        $this->startSqlQueue->add($listener);
    }


    public function onStop(callable $listener)
    {
        $this->stopSplQueue->add($listener);
    }


    public function onBeforeTick(callable $listener)
    {
        $this->nextSqlQueue->add($listener);
    }

    /**
     * [キュー構造] SplQueue　add enqueue
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->futureSqlQueue->add($listener);
    }


    public function tick()
    {
        $this->flowController->isRunning = true;

        $this->nextSqlQueue->tick();
        $this->futureSqlQueue->tick();
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
        $this->startSqlQueue->tick();

        while ($this->flowController->isRunning) {
            $this->nextSqlQueue->tick();

            $this->futureSqlQueue->tick();

            $this->timers->tick();

            // 次ティックまたは将来ティックのキューには、保留中のコールバックがあります
            // Next-tick or future-tick queues have pending callbacks ...
            if (!$this->flowController->isRunning || !$this->nextSqlQueue->isEmpty() || !$this->futureSqlQueue->isEmpty()) {
                $timeout = 0;
            }
            // 保留中のタイマーがあり、期限が来るまでブロックするだけです...
            // There is a pending timer, only block until it is due ...
            else if ($scheduledAt = $this->timers->getFirst()) {
                $timeout = $scheduledAt - $this->timers->getTime();
                $timeout = ($timeout < 0) ? 0 : $timeout * self::MICROSECONDS_PER_SECOND;
            }
            // 可能なイベントはストリーム アクティビティだけなので、いつまでも待ちます...
            // The only possible event is stream activity, so wait forever ...
            else if ($this->readStreams || $this->writeStreams) {
                $timeout = null;
            }
            // やることがなくなった...
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

        $this->stopSplQueue->tick();
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
        $selectQueue = new static();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $this->$key = $selectQueue->$key;
        }

        $this->flowController->isRunning = false;

        return $this;
    }


    public function export(QueueFeatureInterface $queueModel, $all = false)
    {
        $this->stop();
        $queueModel->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $queueModel->$key = $this->$key;
        }

        return $this;
    }


    public function import(QueueFeatureInterface $queueModel, $all = false)
    {
        $this->stop();
        $queueModel->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $this->$key = $queueModel->$key;
        }

        return $this;
    }


    public function swap(QueueFeatureInterface $queueModel, $all = false)
    {
        $this->stop();
        $queueModel->stop();

        $list = $all === true ? $this : $this->getTransferableProperties();
        foreach ($list as $key => $val) {
            $tmp = $queueModel->$key;
            $queueModel->$key = $this->$key;
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
     * https://php.net/manual/en/function.stream-select.php
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
            'nextSqlQueue'     => null,
            'futureSqlQueue'   => null,
            'flowController'    => null
        ];
    }

    public function onTick(callable $listener)
    {
        $this->sqlQueue->onAfterTick($listener);
    }
}
