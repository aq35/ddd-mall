<?php

namespace DDD\UserBoundedContext\UseCase;

use DDD\UserBoundedContext\Service\UserService;


final class RegisterUser
{
    public function registerUser(string $email, string $password):void
    {
        // S3に画像保存をするURLを取得する

        // 利用者登録
        UserService::registerUser($email,$password);

        // メール通知をする
    }
}