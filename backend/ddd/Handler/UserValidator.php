<?php

namespace DDD\Handler;

use DDD\Entity\User;
use DDD\Handler\Validator;
use DesignPattern\Middleware\HandleMiddlewareForClient\PipelineBuilder;
use DesignPattern\Middleware\Conceptions\Input;

use DDD\Handler\EmailIsRFC2821;
use DDD\Handler\ValidationHandler;

class UserValidator extends Validator
{
    private User $user;

    public function __construct(
        User $user
    ) {
        $this->setUser($user);
    }

    public function validate()
    {
        $input = new Input();
        $input->errors = [];
        $input->params['email'] = $this->user->getEmail();
        $pipeline = (new PipelineBuilder)
            ->use(new EmailIsRFC2821()) // EmailがRFCであるか
            ->build(new ValidationHandler()); // Handler登録
        return $pipeline->handle($input);
    }

    protected function setUser(User $user)
    {
        $this->user = $user;
    }
}
