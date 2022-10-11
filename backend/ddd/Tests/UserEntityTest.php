<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DDD\Entity\User;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION | 説明 テストケース
 * ---------------------------------------------------------------------------------------------------------------------
 * 利用者Entityのテストケース
 * User Entity
 * UserValidator バリデーション
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE | 使い方
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * $> ./vendor/bin/phpunit ddd
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */
final class UserEntityTest extends TestCase
{
    public $invalidEmail = 'nagarestarzxc@.com';
    public $validEmail = 'nagarestarzxc@gmail.com';

    public $validPassword = 'Test12345!';
    public $inValidPassword = 'Test12345';

    /*---------------------------------------------------------------------------------------------------------------------
     * 利用者Entity作成するも、メールアドレス不正によるバリデーションエラー
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testRegisterUserInvalidEmail(): void
    {

        $userEntity = User::register(email: $this->invalidEmail, plainPassword: $this->validPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * 利用者Entity作成するも、パスワード不正によるバリデーションエラー
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testRegisterUserInvalidPassword(): void
    {
        $userEntity = User::register(email: $this->validEmail, plainPassword: $this->inValidPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * 利用者Entity作成に成功する
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testRegisterUserValidInput(): void
    {
        $userEntity = User::register(email: $this->validEmail, plainPassword: $this->validPassword);
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * 利用者Entity再生成に成功する
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testRestoreFromSourceValidInput(): void
    {
        $hashPassword = password_hash($this->validPassword, PASSWORD_DEFAULT);
        $userEntity = User::restoreFromSource(
            \DDD\ValueObject\UserId::generate(),
            email: $this->validEmail,
            hashedPassword: $hashPassword
        );
        $userEntity->getUserId();
        $data = $userEntity->validateRestoreFromSource();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }
}
