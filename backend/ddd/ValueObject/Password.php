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
