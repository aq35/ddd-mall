<?php

namespace DDD\ValueObject;

final class UserId
{
    private string $userId;

    public function __construct(
        string $userId
    ) {
        $this->userId = $userId;
    }

    public static function generate()
    {
        return "";
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
