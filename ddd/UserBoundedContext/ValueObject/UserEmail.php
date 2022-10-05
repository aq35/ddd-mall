<?php

namespace DDD\UserBoundedContext\ValueObject;

use DDD\Exception\DomainException;

final class Email
{
    private string $email;
     
    public function __construct(string $email)
    {
        // RFCに沿ったメール型であること
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)|| is_null($email)){
            throw new DomainException('RFCに沿ったメール型であること');
        } 
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}