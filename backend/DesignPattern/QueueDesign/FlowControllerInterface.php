<?php

namespace DesignPattern\QueueDesign;

interface FlowControllerInterface
{
    public function __destruct();

    /**
     * Check if FlowController allows Queue to run.
     *
     * @return bool
     */
    public function isRunning();

    /**
     * Set FlowController to allow Queue to run.
     */
    public function start();

    /**
     * Set FlowController to not allow Queue to run.
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
}
