<?php

namespace DesignPattern\QueueDesign\BaseQueue;

use DesignPattern\QueueDesign\FlowController;
use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;

// [QueueDesign] の状態管理 QueueManagerInterface の拡張
// @return QueueManagerExtendedInterface... $this 返す時と同じで メソッドチェーンができる。
interface QueueManagerExtendedInterface extends QueueManagerInterface
{
    /**
     * @return QueueFeatureInterface
     */
    public function getModel();

    /**
     * QueueFeatureInterface
     * Perform a single iteration of the event Queue.
     */
    public function tick();

    /**
     * QueueFeatureInterface
     * Run the Queue until there are no more tasks to perform.
     */
    public function start();

    /**
     * QueueFeatureInterface
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
     * Erase Queue.
     *
     * @param bool $all
     * @return QueueManagerExtendedInterface
     */
    public function erase($all = false);

    /**
     * Export Queue not fired handlers and/or streams to another Queue model.
     *
     * @param QueueManagerExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueManagerExtendedInterface
     */
    public function export(QueueManagerExtendedInterface $selectQueue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueManagerExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueManagerExtendedInterface
     */
    public function import(QueueManagerExtendedInterface $selectQueue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueManagerExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueManagerExtendedInterface
     */
    public function swap(QueueManagerExtendedInterface $selectQueue, $all = false);
}
