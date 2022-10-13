<?php

namespace DesignPattern\Queue;

use DesignPattern\Queue\QueueExtendedInterface;

interface QueueExtendedAwareInterface
{
    /**
     * Set the Queue of which object is aware of.
     *
     * @param QueueExtendedInterface|null $Queue
     */
    public function setQueue(QueueExtendedInterface $Queue = null);

    /**
     * Return the Queue of which object is aware of.
     *
     * @return QueueExtendedInterface|null
     */
    public function getQueue();
}
