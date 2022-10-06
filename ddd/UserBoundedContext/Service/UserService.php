<?php

namespace DDD\UserBoundedContext\Service;

use DDD\UserBoundedContext\Repogitory\IUserRepogitory;

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
}