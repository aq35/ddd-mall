<?php

namespace DesignPattern\QueueDesign;

use DesignPattern\QueueDesign\QueueManagerExtendedInterface;

interface QueueExtendedAwareInterface
{
    /**
     * Set the Queue of which object is aware of.
     *
     * @param QueueManagerExtendedInterface|null $queue
     */
    public function setQueue(QueueManagerExtendedInterface $queue = null);

    /**
     * Return the Queue of which object is aware of.
     *
     * @return QueueManagerExtendedInterface|null
     */
    public function getQueue();
}
