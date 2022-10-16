<?php

namespace DesignPattern\QueueDesign\ForClient;

use DesignPattern\QueueDesign\BaseQueue\QueueFeatureInterface;
use DesignPattern\QueueDesign\BaseQueue\QueueManagerInterface;

interface SelectQueueInterface extends QueueFeatureInterface, QueueManagerInterface
{
}
