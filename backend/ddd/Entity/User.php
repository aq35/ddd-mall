<?php

namespace DDD\Entity;

use DDD\ValueObject\UserId;
use DDD\ValueObject\Password;
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
     * @var Password
     */
    private Password $password;

    /**
     * ファクトリメソッド
     * @param string $userId
     * @param string $email
     * @param string $hashedPassword
     * @return static
     */
    public static function restoreFromSource(
        string $userId,
        string $email,
        string $hashedPassword,
    ): self {
        $user = new self();
        $user->userId = new UserId($userId);
        $user->email = $email;
        $user->password = Password::restoreFromSource($hashedPassword); // 既にハッシュ化されているため、バリデーションはできない。
        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function register(
        string $email,
        string $plainPassword,
    ): self {
        $user = new self();
        $user->userId = new UserId(UserId::generate());
        $user->email = $email;
        $user->password = Password::register($plainPassword); // ハッシュされる前
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

    // Hash化する前のパスワード
    public function getPassword()
    {
        return $this->password;
    }
}
