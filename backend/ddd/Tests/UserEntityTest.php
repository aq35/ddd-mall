<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DDD\Entity\User;

// ### 利用者Entityのテスト
final class UserEntityTest extends TestCase
{
    public $invalidEmail = 'nagarestarzxc@.com';
    public $validEmail = 'nagarestarzxc@gmail.com';

    public $validPassword = 'Test12345!';
    public $inValidPassword = 'Test12345';

    // メールアドレスが不正
    public function testRegisterUserInvalidEmail(): void
    {

        $userEntity = User::register(self::$invalidEmail, self::$validPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    // パスワードが不正
    public function testRegisterUserInvalidPassword(): void
    {
        $userEntity = User::register(self::$validEmail, self::$inValidPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    // 入力項目が正しい
    public function testRegisterUserValidInput(): void
    {
        $userEntity = User::register(self::$validEmail, self::$validPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }

    // 入力項目が正しい
    public function testRestoreFromSourceValidInput(): void
    {
        $hashPassword = password_hash(self::$validPassword, PASSWORD_DEFAULT);
        $userEntity = User::restoreFromSource(
            \DDD\ValueObject\UserId::generate(),
            self::$validEmail,
            $hashPassword
        );
        $userEntity->getUserId();
        $data = $userEntity->validateRestoreFromSource();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }
}
// ./vendor/bin/phpunit ddd
