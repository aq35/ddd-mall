<?php

namespace DDD\Validator;

use DDD\Validator\ValidationNotificationHandler;

abstract class Validator
{
    private ValidationNotificationHandler $notificationHandler;

    public function __construct(
        ValidationNotificationHandler $aHandler,
    ) {
        $this->setNotificationHandler($aHandler);
    }

    public abstract function validate(): void;


    protected function notificationHandler(): ValidationNotificationHandler
    {
        return $this->notificationHandler;
    }

    protected function setNotificationHandler(ValidationNotificationHandler $aHandler)
    {
        $this->notificationHandler = $aHandler;
    }
}
