<?php

namespace DDD\UserBoundedContext\Entity;

use DDD\UserBoundedContext\Entity\UserProfile;
use DDD\UserBoundedContext\ValueObject\UserId;
use DDD\UserBoundedContext\ValueObject\UserPassword;
use DDD\UserBoundedContext\ValueObject\UserEmail;

final class User
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var UserEmail
     */
    private UserEmail $email;

    /**
     * @var UserProfile|null
     */
    private ?UserProfile $profile;

    /**
     * @var UserPassword
     */
    private UserPassword $password;

    /**
     * ファクトリメソッド
     * @param UserId $id
     * @param UserEmail $email
     * @param UserPassword $password
     * @param UserProfile|null $profile
     * @return static
     */
    public static function restoreFromSource(
        UserId $id,
        UserEmail $email,
        UserPassword $password,
        ?UserProfile $profile
    ): self
    {
        $user = new self();
        $user->id = $id;
        $user->email = $email;
        $user->password = $password;
        $user->profile = $profile;
        return $user;
    }

    /**
     * @param UserEmail $email
     * @param UserPassword $password
     * @return static
     */
    public static function register(
        UserEmail $email,
        UserPassword $password,
        ?UserProfile $profile
    ): self
    {   
        $user = new self();
        $user->id = UserId::generate();
        $user->email = $email;
        $user->password = $password;
        $user->profile = $profile;
        return $user;
    }
}