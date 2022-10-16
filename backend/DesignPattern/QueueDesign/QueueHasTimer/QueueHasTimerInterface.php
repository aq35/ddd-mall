<?php

namespace DesignPattern\QueueDesign\QueueHasTimer;

use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;

interface QueueHasTimerInterface
{
    /**
     * Return Queue.
     *
     * @return QueueFeatureInterface
     */
    public function getQueue();

    /**
     * Check if timer is active.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Cancel timer and unregister it from Queue.
     */
    public function cancel();
}
