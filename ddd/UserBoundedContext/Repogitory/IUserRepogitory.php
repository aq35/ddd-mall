<?php

namespace DDD\UserBoundedContext\Repogitory;

interface IUserRepogitory
{
    public static function findUserEmail($userId);
    public static function registerUser();
}