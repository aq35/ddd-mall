<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;

// クライアントに [QueueDesign] を提供する
interface QueueDesignAwareInterface
{
    /**
     * クライアントは、setQueueを実装する。
     * [QueueDesign]の状態管理
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param QueueManagerInterface $queue
     */
    public function setQueue(QueueManagerInterface $queue = null);

    /**
     * クライアントは、setQueueを実装する。
     * [QueueDesign]の状態管理
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return QueueManagerInterface|null
     */
    public function getQueue();
}
