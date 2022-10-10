<?php

namespace DDD\Handler;

use DDD\Entity\User;
use DDD\Handler\UserValidationNotificationHandler;
use DDD\Handler\Validator;
use DDD\Handler\ValidationNotificationHandler;

class UserValidator extends Validator
{
    private User $user;

    public function __construct(
        User $user,
        ValidationNotificationHandler $aHandler,
    ) {
        $this->setNotificationHandler($aHandler);
        $this->setUser($user);
    }

    public function validate(): void
    {
        $this->checkValidEmailForUser();
    }

    protected function checkValidEmailForUser()
    {
        if ($this->user->email) {
            $this->userNotificationHandler()->handleErrorMessage("This specific validation failed");
        }
    }

    protected function userNotificationHandler(): UserValidationNotificationHandler
    {
        return $this->notificationHandler();
    }

    protected function setUser(User $user)
    {
        $this->user = $user;
    }
}
