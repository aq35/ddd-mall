<?php

namespace DesignPattern\QueueDesign;

use DesignPattern\QueueDesign\FlowController;
use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;

// [QueueDesign] の状態管理 QueueManagerInterface の拡張
// @return QueueExtendedInterface　... $this 返す時と同じで メソッドチェーンができる。
interface QueueExtendedInterface extends QueueManagerInterface
{
    /**
     * @return QueueFeatureInterface
     */
    public function getModel();

    /**
     * Perform a single iteration of the event Queue.
     */
    public function tick();

    /**
     * Run the Queue until there are no more tasks to perform.
     */
    public function start();

    /**
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
     * @return QueueExtendedInterface
     */
    public function erase($all = false);

    /**
     * Export Queue not fired handlers and/or streams to another Queue model.
     *
     * @param QueueExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function export(QueueExtendedInterface $selectQueue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function import(QueueExtendedInterface $selectQueue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueExtendedInterface $selectQueue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function swap(QueueExtendedInterface $selectQueue, $all = false);
}
