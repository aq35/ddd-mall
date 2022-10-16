<?php

namespace DesignPattern\Queue;

use DesignPattern\Queue\FlowController;
use DesignPattern\Queue\BaseQueue\QueueInterface;

interface QueueExtendedInterface extends QueueInterface
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
     * @param QueueExtendedInterface $Queue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function export(QueueExtendedInterface $Queue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueExtendedInterface $Queue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function import(QueueExtendedInterface $Queue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueExtendedInterface $Queue
     * @param bool $all
     * @return QueueExtendedInterface
     */
    public function swap(QueueExtendedInterface $Queue, $all = false);
}
