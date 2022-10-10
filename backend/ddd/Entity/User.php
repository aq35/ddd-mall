<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\Entity\BaseEntity;
use DDD\Entity\UserValidator;

final class User extends BaseEntity
{
    /**
     * @var UserId
     */
    private UserId $userId;

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
     * @param UserId $userId
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function restoreFromSource(
        UserId $userId,
        string $email,
        string $password,
    ): self {
        $user = new self();
        $user->userId = $userId;
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
        $user->userId = new UserId(UserId::generate());
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT); // ハッシュされる前
        $user->registerPassword = $password;
        return $user;
    }

    // バリデーションの実装
    public function validate()
    {
        return (new UserValidator($this))->validate();
    }

    public function getUserId()
    {
        return $this->userId;
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
