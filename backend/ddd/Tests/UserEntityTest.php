<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DDD\Entity\User;

final class UserEntityTest extends TestCase
{
    public function testUserInvalidEmail(): void
    {
        $userEntity = User::register('nagarestarzxc@.com', 'Test12345!');
        $userEntity->getUserId();
        $data = $userEntity->validate();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    public function testUserInvalidPassword(): void
    {
        $userEntity = User::register('nagarestarzxc@gmail.com', 'Test12345');
        $userEntity->getUserId();
        $data = $userEntity->validate();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) > 0);
    }

    public function testUserValidInput(): void
    {
        $userEntity = User::register('nagarestarzxc@gmail.com', 'Test12345!');
        $userEntity->getUserId();
        $data = $userEntity->validate();
        echo json_encode($data->errors, JSON_UNESCAPED_UNICODE) . "\n";
        $this->assertTrue(count($data->errors) == 0);
    }
}
// vendor/bin/phpunit ddd
