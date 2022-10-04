<?php

namespace DDD\User\Entity;

use DDD\Validator\FreeMailValidator;
use DDD\Exception\DomainException;
use DDD\User\ValueObject\UserId;
use DDD\User\ValueObject\UserProfile;
use DDD\User\ValueObject\UserPassword;

final class User
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var UserProfile|null
     */
    private ?UserProfile $profile;

    /**
     * @var UserPassword
     */
    private UserPassword $password;

    /**
     * User constructor.
     */
    private function __construct()
    {
    }

    /**
     * ファクトリメソッド
     * @param UserId $id
     * @param string $email
     * @param UserPassword $password
     * @param UserProfile|null $profile
     * @return static
     */
    public static function restoreFromSource(
        UserId $id,
        string $email,
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
     * @param string $email
     * @param UserPassword $password
     * @return static
     */
    public static function register(
        string $email,
        UserPassword $password
    ): self
    {
        if (!FreeMailValidator::isValid($email))
            throw new DomainException('Free email was detected.');
        
        $user = new self();
        $user->id = UserId::generate();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setProfile(null);
        return $user;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return UserProfile|null
     */
    public function getProfile(): ?UserProfile
    {
        return $this->profile;
    }

    /**
     * @param UserProfile|null $profile
     */
    public function setProfile(?UserProfile $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return UserPassword
     */
    public function getPassword(): UserPassword
    {
        return $this->password;
    }

    /**
     * @param UserPassword $password
     */
    public function setPassword(UserPassword $password): void
    {
        $this->password = $password;
    }
}