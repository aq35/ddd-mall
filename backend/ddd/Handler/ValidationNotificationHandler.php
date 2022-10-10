<?php

namespace DDD\Handler;

interface ValidationNotificationHandler
{
    public function handleErrorMessage(string $aNotificationMessage);

    public function handleError(string $aNotification, $anObject);

    public function handleInfoMessage(string $aNotificationMessage);

    public function handleInfo(string $aNotification, $anObject);

    public function handleWarningMessage(string $aNotificationMessage);

    public function handleWarning(string $aNotification, $anObject);
}
