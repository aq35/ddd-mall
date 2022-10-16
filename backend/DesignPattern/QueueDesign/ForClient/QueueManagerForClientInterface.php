<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\ForClient\SelectQueueInterface;

// クライアントに [QueueDesign] QueueManager 状態管理 のアクセスを提供する
interface QueueManagerForClientInterface
{
    /**
     * クライアントは、setQueueを実装する。
     * [QueueDesign]の状態管理
     * Set the Queue of which object is aware of or delete is setting to null.
     *
     * @param SelectQueueInterface $queue
     */
    public function setQueue(SelectQueueInterface $queue = null);

    /**
     * クライアントは、setQueueを実装する。
     * [QueueDesign]の状態管理
     * Return the Queue of which object is aware of or null if none was set.
     *
     * @return SelectQueueInterface|null
     */
    public function getQueue();
}
