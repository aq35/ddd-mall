<?php

namespace DDD\Validator;

interface ValidationNotificationHandler
{
    public function handleError(string $aNotificationMessage): void;

    public function handleErrorWithObject(string $aNotification, $anObject): void;

    public function handleInfo(string $aNotificationMessage): void;

    public function handleInfoWithObject(string $aNotification, $anObject): void;

    public function handleWarning(string $aNotificationMessage): void;

    public function handleWarningWithObject(string $aNotification, $anObject);
}
