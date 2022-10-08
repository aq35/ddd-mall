<?php

namespace DDD\Repogitory;

interface IUserRepogitory
{
    public static function findUserEmail($userId);
    public static function registerUser();
}
