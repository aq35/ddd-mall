<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\Entity\BaseEntity;
use DDD\Handler\ValidationNotificationHandler;

final class User extends BaseEntity
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

    public function validate(ValidationNotificationHandler $aHandler): void
    {
        (new \DDD\Handler\UserValidator($this, $aHandler))->validate();
    }
}
