<?php

namespace DDD\ValueObject;

final class Password
{
    private string $hashedPassword;
    private string|null $plainPassword;

    public static function restoreFromSource(string $hashedPassword)
    {
        $password = new self();
        $password->plainPassword = null;
        $password->hashedPassword = $hashedPassword;
        return $password;
    }

    public static function register(string $plainPassword)
    {
        $password = new self();
        $password->plainPassword = $plainPassword;
        $password->password = password_hash($plainPassword, PASSWORD_DEFAULT);
        return $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }
}
