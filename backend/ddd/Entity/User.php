<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\Entity\BaseEntity;

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
    private string $registerPassword;

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
        $user->password = $password; // 既にハッシュ化されている
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
        $user->id = new UserId(UserId::generate());
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT); // ハッシュされる前
        $user->registerPassword = $password;
        return $user;
    }

    public function validate()
    {
        return (new \DDD\Handler\UserValidator($this))->validate();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRegisterPassword()
    {
        return $this->registerPassword;
    }
}
