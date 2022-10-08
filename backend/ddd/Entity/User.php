<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\ValueObject\UserEmail;

final class User
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * ファクトリメソッド
     * @param UserId $id
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function restoreFromSource(
        UserId $id,
        string $email,
        string $password,
    ): self {
        $user = new self();
        $user->id = $id;
        $user->email = $email;
        $user->password = $password;
        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function register(
        string $email,
        string $password,
    ): self {
        $user = new self();
        $user->id = UserId::generate();
        $user->email = $email;
        $user->password = $password;
        return $user;
    }
}
