<?php

namespace DDD\ValueObject;

use DDD\ValueObject\BaseValueObject\BaseValueObject;

final class UserId extends BaseValueObject
{
    private string $userId;

    public function __construct(
        string $userId
    ) {
        $this->userId = $userId;
    }

    public static function generate()
    {
        return new UserId(parent::generateId());
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
