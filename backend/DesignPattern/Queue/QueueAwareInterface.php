<?php

namespace DesignPattern\Queue;

interface QueueAwareInterface
{
    /**
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param QueueInterface $queue
     */
    public function setQueue(QueueInterface $queue = null);

    /**
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return QueueInterface|null
     */
    public function getQueue();
}
