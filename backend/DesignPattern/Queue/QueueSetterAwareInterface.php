<?php

namespace DesignPattern\Queue;

interface QueueSetterAwareInterface
{
    /**
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param QueueInterface $Queue
     */
    public function setQueue(QueueInterface $Queue = null);
}
