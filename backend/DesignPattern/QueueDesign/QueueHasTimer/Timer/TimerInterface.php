<?php

namespace DesignPattern\QueueDesign\QueueHasTimer\Timer;

// [Timer] 時間
interface TimerInterface
{
    /**
     * Return interval of timer.
     *
     * @return float
     */
    public function getInterval();

    /**
     * Return callback attached to timer.
     *
     * @return callable
     */
    public function getCallback();

    /**
     * Return data associated with timer.
     *
     * @return mixed|null
     */
    public function getData();

    /**
     * Set data associated with timer.
     *
     * @param mixed $data
     */
    public function setData($data);

    /**
     * Check if timer is periodic.
     *
     * @return bool
     */
    public function isPeriodic();
}
