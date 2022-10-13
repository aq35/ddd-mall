<?php

namespace DesignPattern\Queue;

interface QueueAwareInterface
{
    /**
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return QueueInterface|null
     */
    public function getQueue();

    /**
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param QueueInterface $Queue
     */
    public function setQueue(QueueInterface $Queue = null);
}
