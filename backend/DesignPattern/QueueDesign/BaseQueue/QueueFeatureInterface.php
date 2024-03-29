<?php

namespace DesignPattern\QueueDesign\BaseQueue;

use Dazzle\QueueDesign\Flow\FlowController;

// [QueueDesign] QueueFeatureInterface 外部 or 内部 Queue機能を操作します。
// どんな Queue を使っているか。
// 例) SqlQueue ... PHPが提供しているQueueサービスです。
// 実際には、QueueFeatureInterfaceは、どんなQueueサービスを使っているかは考えなくて良い。
interface QueueFeatureInterface
{
    public function getModel();

    /**
     * Perform a single iteration of the event Queue.
     */
    public function tick();

    /**
     * Run the event Queue until there are no more tasks to perform.
     */
    public function start();

    /**
     * stopすることでループは閉じる
     * Instruct a running event Queue to stop.
     */
    public function stop();

    /**
     * Set FlowController used by model.
     *
     * @param mixed $flowController
     */
    public function setFlowController($flowController);

    /**
     * Return FlowController used by model.
     *
     * @return FlowController
     */
    public function getFlowController();

    /**
     * Flush Queue.
     *
     * @param bool $all
     * @return QueueFeatureInterface
     */
    public function erase($all = false);

    /**
     * Export Queue not fired handlers and/or streams to another Queue model.
     *
     * @param QueueFeatureInterface $queue
     * @param bool $all
     * @return QueueFeatureInterface
     */
    public function export(QueueFeatureInterface $queue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueFeatureInterface $queue
     * @param bool $all
     * @return QueueFeatureInterface
     */
    public function import(QueueFeatureInterface $queue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueFeatureInterface $queue
     * @param bool $all
     * @return QueueFeatureInterface
     */
    public function swap(QueueFeatureInterface $queue, $all = false);
}
