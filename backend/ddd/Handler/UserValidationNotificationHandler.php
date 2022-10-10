<?php

namespace DDD\Handler;

use DDD\Handler\ValidationNotificationHandler;

// 利用者専用のバリデーション
class UserValidationNotificationHandler implements ValidationNotificationHandler
{
    public function handleErrorMessage(string $aNotificationMessage)
    {
    }

    public function handleError(string $aNotification, $anObject)
    {
    }

    public function handleInfoMessage(string $aNotificationMessage)
    {
    }

    public function handleInfo(string $aNotification, $anObject)
    {
    }

    public function handleWarningMessage(string $aNotificationMessage)
    {
    }

    public function handleWarning(string $aNotification, $anObject)
    {
    }
}
