<?php

namespace DDD\UserBoundedContext\Service;

use DDD\UserBoundedContext\Repogitory\IUserRepogitory;
use DDD\UserBoundedContext\Entity\User;
use DDD\UserBoundedContext\ValueObject\UserEmail;
use DDD\UserBoundedContext\ValueObject\UserPassword;
use Exception;

final class UserService
{
    // 利用者のメールアドレスが一意である
    public static function isEmailUnique($email):bool
    {
        $user = IUserRepogitory::findUserEmail($email);
        if(is_null($user)){
            return true;    
        }else{
            return false;
        }
    }

    public static function registerUser(string $email, string $password):void
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