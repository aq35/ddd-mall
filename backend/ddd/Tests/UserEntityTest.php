<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DDD\Entity\User;

// ### 利用者Entityのテスト
final class UserEntityTest extends TestCase
{
    public function testRegisterUserInvalidEmail(): void
    {
        $userEntity = User::register('nagarestarzxc@.com', 'Test12345!');
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    public function testRegisterUserInvalidPassword(): void
    {
        $userEntity = User::register('nagarestarzxc@gmail.com', 'Test12345');
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    public function testRegisterUserValidInput(): void
    {
        $userEntity = User::register('nagarestarzxc@gmail.com', 'Test12345!');
        $userEntity->getUserId();
        $data = $userEntity->validateRegister();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }

    public function testRestoreFromSourceValidInput(): void
    {
        $userEntity = User::restoreFromSource(
            \DDD\ValueObject\UserId::generate(),
            'nagarestarzxc@gmail.com',
            password_hash('Test12345!', PASSWORD_DEFAULT)
        );
        $userEntity->getUserId();
        $data = $userEntity->validateRestoreFromSource();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }
}
// ./vendor/bin/phpunit ddd
