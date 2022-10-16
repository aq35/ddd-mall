<?php

namespace DesignPattern\Queue;

use DesignPattern\Queue\BaseQueue\QueueManagerInterface;

interface QueueAwareInterface
{
    /**
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param QueueManagerInterface $queue
     */
    public function setQueue(QueueManagerInterface $queue = null);

    /**
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return QueueManagerInterface|null
     */
    public function getQueue();
}
