<?php

namespace DesignPattern\Event\Domain;

class EventEmitterMode
{
    /**
     * @var int
     */
    const EVENTS_DEFAULT = 0;

    /**
     * @var int
     */
    const EVENTS_FORWARD = 0;

    /**
     * @var int
     */
    const EVENTS_DISCARD = 3;

    /**
     * @var int
     */
    const EVENTS_DISCARD_INCOMING = 1;

    /**
     * @var int
     */
    const EVENTS_DISCARD_OUTCOMING = 2;
}
