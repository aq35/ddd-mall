<?php

namespace DesignPattern\QueueDesign;

// [FlowController] 閉じたり(false)、開いたり(true)する。最初は開いていると[Queue]を利用できる
class FlowController
{
    /**
     * @var bool
     */
    public $isRunning;

    /**
     *
     */
    public function __construct()
    {
        $this->isRunning = false;
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->isRunning);
    }

    /**
     * Check if FlowController allows Queue to run.
     *
     * @return bool
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * Set FlowController to allow Queue to run.
     */
    public function start()
    {
        $this->isRunning = true;
    }

    /**
     * Set FlowController to not allow Queue to run.
     */
    public function stop()
    {
        $this->isRunning = false;
    }
}
