<?php

namespace DDD\Entity;

use DDD\Entity\User;
use DDD\Validator\UserValidationNotificationHandler;
use DDD\Validator\Validator;
use DDD\Validator\ValidationNotificationHandler;

class UserValidator extends Validator
{
    private User $user;

    public function __construct(
        User $user,
        ValidationNotificationHandler $aHandler,
    ) {
        parent::setNotificationHandler($aHandler);
        $this->setUser($user);
    }

    public function validate(): void
    {
        $this->checkForUserValidEmail();
    }

    protected function checkForUserValidEmail()
    {
        if ($this->user) {
            $this->userNotificationHandler()->handleUser();
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
