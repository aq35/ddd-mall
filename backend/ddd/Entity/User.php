<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\Entity\BaseEntity;
use DDD\Entity\UserValidator;

final class User extends BaseEntity
{
    // 正規表現を用いて8文字以上アルファベットの大文字小文字、数字、記号を含める
    const VALID_PASSWORD_REGEX = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[\W_])[!-~]{8,}+\z/';

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
    private string $passwordRegister;

    /**
     * ファクトリメソッド
     * @param string $userId
     * @param string $email
     * @param string $hashPassword
     * @return static
     */
    public static function restoreFromSource(
        string $userId,
        string $email,
        string $hashPassword,
    ): self {
        $user = new self();
        $user->userId = new UserId($userId);
        $user->email = $email;
        $user->password = $hashPassword; // 既にハッシュ化されているため、バリデーションはできない。
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
        $user->passwordRegister = $password;
        return $user;
    }

    // バリデーションの実装
    public function validateRegister()
    {
        return (new UserValidator($this))->validateRegister();
    }

    public function validateRestoreFromSource()
    {
        return (new UserValidator($this))->validateRestoreFromSource();
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

    // Hash化する前のパスワード
    public function getPasswordRegister()
    {
        return $this->passwordRegister;
    }
}
