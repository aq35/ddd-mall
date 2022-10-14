<?php

namespace DesignPattern\Queue;

use Dazzle\Queue\Flow\FlowController;

interface QueueModelInterface
{
    /**
     * Perform a single iteration of the event Queue.
     */
    public function tick();

    /**
     * Run the event Queue until there are no more tasks to perform.
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
     * Flush Queue.
     *
     * @param bool $all
     * @return QueueModelInterface
     */
    public function erase($all = false);

    /**
     * Export Queue not fired handlers and/or streams to another Queue model.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function export(QueueModelInterface $Queue, $all = false);

    /**
     * Import handlers and/or streams from another Queue model.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function import(QueueModelInterface $Queue, $all = false);

    /**
     * Swap handlers and/or stream between Queue models.
     *
     * @param QueueModelInterface $Queue
     * @param bool $all
     * @return QueueModelInterface
     */
    public function swap(QueueModelInterface $Queue, $all = false);
}
