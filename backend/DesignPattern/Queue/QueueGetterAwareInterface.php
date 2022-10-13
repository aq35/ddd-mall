<?php

namespace DesignPattern\Queue;

interface QueueGetterAwareInterface
{
    /**
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return QueueInterface|null
     */
    public function getQueue();
}
