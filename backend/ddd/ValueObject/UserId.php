<?php

namespace DDD\ValueObject;

use Symfony\Component\Uid\Ulid;

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
        return Ulid::generate();
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
