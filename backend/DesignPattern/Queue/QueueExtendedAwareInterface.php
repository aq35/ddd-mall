<?php

namespace DesignPattern\Queue;

use DesignPattern\Queue\QueueExtendedInterface;

interface QueueExtendedAwareInterface
{
    /**
     * Set the Queue of which object is aware of.
     *
     * @param QueueExtendedInterface|null $queue
     */
    public function setQueue(QueueExtendedInterface $queue = null);

    /**
     * Return the Queue of which object is aware of.
     *
     * @return QueueExtendedInterface|null
     */
    public function getQueue();
}
