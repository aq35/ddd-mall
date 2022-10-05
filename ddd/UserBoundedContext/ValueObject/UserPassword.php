<?php

namespace DDD\UserBoundedContext\ValueObject;

use DDD\Exception\DomainException;

final class UserPassword
{
    const MIN = 8;
    private string $password;
     
    public function _construct(string $password)
    {
        // 8文字以上
        if (mb_strlen($password) < self::MIN) {
            throw new DomainException('RFCに沿ったメール型であること');
        }
        $this->password = $password;
    }
     
    public function getPassword():string
    {
        return $this->password;
    }
}