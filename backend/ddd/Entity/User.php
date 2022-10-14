<?php

namespace DDD\Entity;

use DDD\Entity\BaseEntity\BaseEntity;

use DDD\ValueObject\UserId;
use DDD\ValueObject\Password;
use DDD\Entity\UserValidator;

/*---------------------------------------------------------------------------------------------------------------------
* DESCRIPTION | 説明
* ---------------------------------------------------------------------------------------------------------------------
*  ### User
* ---------------------------------------------------------------------------------------------------------------------
* USAGE | 使い方
* ---------------------------------------------------------------------------------------------------------------------
*　restoreFromSource 永続化の再生成
*  validateRestoreFromSource 再生成した後のUser(Entity)のバリデーションチェック
*  register 新規作成
*  validateRegister 新規作成した後のUser(Entity)のバリデーションチェック
* ---------------------------------------------------------------------------------------------------------------------*/

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
     * @var Password
     */
    private Password $password;

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
        $user->userId = UserId::generate();
        $user->email = $email;
        $user->password = Password::register($plainPassword); // ハッシュされる前
        return $user;
    }

    /**
     * ファクトリメソッド
     * @param UserId $userId
     * @param string $email
     * @param string $hashedPassword
     * @return static
     */
    public static function restoreFromSource(
        UserId $userId,
        string $email,
        string $hashedPassword,
    ): self {
        $user = new self();
        $user->userId = $userId;
        $user->email = $email;
        $user->password = Password::restoreFromSource($hashedPassword); // 既にハッシュ化されているため、バリデーションはできない。
        return $user;
    }

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
