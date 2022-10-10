<?php

namespace DDD\Entity;

use DDD\Handler\ValidationNotificationHandler;
// Entityの設計
abstract class BaseEntity
{
    public function validate(ValidationNotificationHandler $aHandler): void
    {
    }
}
