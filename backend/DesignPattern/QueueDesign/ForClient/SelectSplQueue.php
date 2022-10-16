<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\SplQueue\SelectQueueInterface;
use DesignPattern\QueueDesign\SplQueue\ContinousSplQueue;
use DesignPattern\QueueDesign\SplQueue\FiniteSplQueue;

use DesignPattern\QueueDesign\QueueHasTimer\QueueHasTimer;
use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerBox;
use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerInterface;

use DesignPattern\QueueDesign\FlowController;
use DesignPattern\QueueDesign\SplQueue\QueueFeatureInterface;

class SelectSplQueue implements SelectQueueInterface
{
    /**
     * @var int
     */
    const MICROSECONDS_PER_SECOND = 1e6;

    /**
     * [PHP SplQueue]
     * @var ContinousSplQueue
     */
    protected $onStartSqlQueue;

    /**
     * [PHP SplQueue]
     * @var ContinousSplQueue
     */
    protected $stopSplQueue;

    /**
     * [PHP SplQueue]
     * @var ContinousSplQueue
     */
    protected $onBeforeSqlQueue;

    /**
     * [PHP SplQueue]
     * @var FiniteSplQueue
     */
    protected $onAfterSqlQueue;

    /**
     * @var FlowController
     */
    protected $flowController; // 閉じたり(false)、開いたり(true)する。最初は開いていると[Queue]は利用できる

    /**
     * @var TimerBox
     */
    protected $timers;

    /**
     * [PHPリソース]
     * @var resource[]
     */
    protected $readStreams = [];

    /**
     * [PHPリソース]
     * @var callable[]
     */
    protected $readListeners = [];

    /**
     * [PHPリソース]
     * @var resource[]
     */
    protected $writeStreams = [];

    /**
     * [PHPリソース]
     * @var callable[]
     */
    protected $writeListeners = [];


    public function __construct()
    {
        $this->onStartSqlQueue = new ContinousSplQueue($this); // [PHP SplQueue]
        $this->stopSplQueue = new ContinousSplQueue($this); // [PHP SplQueue]
        $this->onBeforeSqlQueue = new ContinousSplQueue($this); // [PHP SplQueue]
        $this->onAfterSqlQueue = new FiniteSplQueue($this); // [PHP SplQueue]

        $this->flowController = new FlowController();
        $this->timers = new TimerBox();
    }

    /**
     *
     */
    public function __destruct()
    {
        // [PHP SplQueue]
        unset($this->onStartSqlQueue); // [PHP SplQueue]
        unset($this->onBeforeSqlQueue); // [PHP SplQueue]
        unset($this->stopSplQueue); // [PHP SplQueue]
        unset($this->onAfterSqlQueue); // [PHP SplQueue]

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

    // [PHPリソース]
    public function addReadStream($stream, callable $listener)
    {
        $key = (int) $stream;

        if (!isset($this->readStreams[$key])) {
            $this->readStreams[$key] = $stream;
            $this->readListeners[$key] = $listener;
        }
    }

    // [PHPリソース]
    public function addWriteStream($stream, callable $listener)
    {
        $key = (int) $stream;

        if (!isset($this->writeStreams[$key])) {
            $this->writeStreams[$key] = $stream;
            $this->writeListeners[$key] = $listener;
        }
    }

    // [PHPリソース]
    public function removeReadStream($stream)
    {
        $key = (int) $stream;

        unset(
            $this->readStreams[$key],
            $this->readListeners[$key]
        );
    }
    // [PHPリソース]
    public function removeWriteStream($stream)
    {
        $key = (int) $stream;

        unset(
            $this->writeStreams[$key],
            $this->writeListeners[$key]
        );
    }
    // [PHPリソース]
    public function removeStream($stream)
    {
        $this->removeReadStream($stream);
        $this->removeWriteStream($stream);
    }

    public function addTimer($interval, callable $callback)
    {
        // SelectQueueを操作できる＋タイマー
        $timer = new QueueHasTimer(
            selectQueue: $this,
            interval: $interval,
            callback: $callback,
            periodic: false
        );

        $this->timers->add($timer); // SelectQueueを操作できる＋タイマーに入れる。

        return $timer;
    }

    public function addPeriodicTimer($interval, callable $callback)
    {
        $timer = new QueueHasTimer(
            selectQueue: $this,
            interval: $interval,
            callback: $callback,
            periodic: true
        );

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

    // [PHP SplQueue]
    public function onStart(callable $listener)
    {
        $this->onStartSqlQueue->add($listener);
    }

    // [PHP SplQueue]
    public function onStop(callable $listener)
    {
        $this->stopSplQueue->add($listener);
    }

    // [PHP SplQueue]
    public function onBeforeTick(callable $listener)
    {
        $this->onBeforeSqlQueue->add($listener);
    }

    /**
     * [PHP SplQueue]
     * @override
     * @inheritDoc
     */
    public function onAfterTick(callable $listener)
    {
        $this->onAfterSqlQueue->add($listener);
    }


    public function tick()
    {
        $this->flowController->isRunning = true;

        $this->onBeforeSqlQueue->tick(); // [PHP SplQueue]
        $this->onAfterSqlQueue->tick(); // [PHP SplQueue]
        $this->timers->tick();
        $this->waitForStreamActivity(
            timeout: 0
        );

        $this->flowController->isRunning = false;
    }


    // client がstartすることで、キューが実行される。
    public function start()
    {
        if ($this->flowController->isRunning) {
            return;
        }

        $this->addPeriodicTimer(interval: 1, callback: function () {
            usleep(1);
        });

        $this->flowController->isRunning = true;

        // onStart();
        // 例) new AsyncEventEmitter()->getQueue()->onStart()
        $this->onStartSqlQueue->tick();

        while ($this->flowController->isRunning) {
            // onBeforeTick();
            // 例) new AsyncEventEmitter()->getQueue()->onBeforeTick();
            $this->onBeforeSqlQueue->tick(); // [PHP SplQueue]

            // onAfterTick();
            // 例) new AsyncEventEmitter()->getQueue()->onAfterTick();
            $this->onAfterSqlQueue->tick(); // [PHP SplQueue]

            // Timers -----
            $this->timers->tick();

            // 次ティックまたは将来ティックのキューには、保留中のコールバックがあります
            // Next-tick or future-tick queues have pending callbacks ...
            if (!$this->flowController->isRunning || !$this->onBeforeSqlQueue->isEmpty() || !$this->onAfterSqlQueue->isEmpty()) {
                $timeout = 0;
            }
            // 保留中のタイマーがあり、期限が来るまでブロックする
            // There is a pending timer, only block until it is due ...
            // 優先順位の高い保留中のタイマー取り出す。Timerが取り出せるまで
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
                // whileを抜ける。
                break;
            }
            // Timers -----

            // [PHPリソース]
            $this->waitForStreamActivity(timeout: $timeout);
        }
    }

    public function stop()
    {
        if (!$this->flowController->isRunning) {
            return;
        }

        $this->stopSplQueue->tick(); // [PHP SplQueue]
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

        if ($this->streamSelect(read: $read, write: $write, timeout: $timeout) === false) {
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
     * 秒とマイクロ秒で指定されたタイムアウトで、指定されたストリームの配列で select() システムコールと同等のものを実行します
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
        echo json_encode($read);
        if ($read || $write) {
            $except = null;
            // 配列にリストされたストリーム read、文字が読み取り可能になるかどうかを確認するために監視されます.
            // 配列にリストされたストリームはwrite、書き込みがブロックされないかどうかを確認するために監視されます。
            // 配列にリストされたストリームは、except優先度の高い例外的な (「帯域外」) データの到着を監視されます。
            // 変更された配列に含まれるストリーム リソースの数を返します。
            return @stream_select(read: $read, write: $write, except: $except, seconds: $timeout === null ? null : 0, microseconds: $timeout);
        }

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
            'onBeforeSqlQueue'   => null,
            'onAfterSqlQueue'  => null,
            'flowController'  => null
        ];
    }

    public function getModel()
    {
        return $this;
    }

    public function onTick(callable $listener)
    {
        return $this->tick($listener);
    }
}
