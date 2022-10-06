<?php

namespace DDD\UserBoundedContext\UseCase;

use DDD\UserBoundedContext\Entity\User;
use DDD\UserBoundedContext\ValueObject\UserEmail;
use DDD\UserBoundedContext\ValueObject\UserPassword;
use DDD\UserBoundedContext\Service\UserService;
use DDD\UserBoundedContext\Repogitory\IUserRepogitory;
use Exception;

final class RegisterUser
{
    public function registerUser(string $email, string $password)
    {
        // メールアドレスが一意であること
        if(UserService::isEmailUnique($email)){
            $userEntity = User::register(new UserEmail($email), new UserPassword($password), null);
            IUserRepogitory::registerUser();
        }else{
            throw (new Exception);
        }
    }
}