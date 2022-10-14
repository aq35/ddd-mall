<?php

namespace DDD\ValueObject;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION | 説明
 * ---------------------------------------------------------------------------------------------------------------------
 * パスワード 値オブジェクト
 *
 * 本クラスには、$hashedPasswordと、$plainPasswordがあります。
 * $hashedPassword は、ハッシュ化された値です。永続データを再生成する場合にセットされます。
 * $plainPassword は、平文です。主に入力チェックを行います。
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE | 使い方
 * ---------------------------------------------------------------------------------------------------------------------
 * restoreFromSource(string $hashedPassword) ... 例) Xdsfsdfdsjsancwerfdnsf4
 * register(string $plainPassword) $plainPassword ... 例) test1234!
 * ---------------------------------------------------------------------------------------------------------------------
 */

use DDD\ValueObject\BaseValueObject\BaseValueObject;

final class Password extends BaseValueObject
{
    private string $hashedPassword;
    private string|null $plainPassword;

    // ハッシュ化パスワード
    public static function restoreFromSource(string $hashedPassword)
    {
        $password = new self();
        $password->hashedPassword = $hashedPassword;
        $password->plainPassword = null;
        return $password;
    }

    // 平文パスワード
    public static function register(string $plainPassword)
    {
        $password = new self();
        $password->hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $password->plainPassword = $plainPassword;
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
