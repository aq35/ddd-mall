<?php

namespace DDD\ValueObject;

use Symfony\Component\Uid\Ulid as UidUlid;

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
        return UidUlid::generate();
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
