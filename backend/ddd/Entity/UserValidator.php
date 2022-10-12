<?php

namespace DDD\Entity;

use DDD\Validator\Validator;

use DDD\Entity\User;
use DDD\Validator\Rules\EmailIsRFC2821;
use DDD\Validator\Rules\PasswordIsSafe;

/*---------------------------------------------------------------------------------------------------------------------
* DESCRIPTION | 説明
* ---------------------------------------------------------------------------------------------------------------------
*  ### UserValidator
*  UserValidatorクラスは、PipelineBuilderクラスのクライアントです。
*  PipelineBuilderのmiddlewareをセットする実装は、親クラスであるValidatorが行っています。
*
* ---------------------------------------------------------------------------------------------------------------------
* USAGE | 使い方 バリデーションチェック
* ---------------------------------------------------------------------------------------------------------------------
*　$pipeline = $this->useMiddleware([
*　new EmailIsRFC2821(),
*　]);
*　$output = $pipeline->handle($input);
* ---------------------------------------------------------------------------------------------------------------------*/

class UserValidator extends Validator
{
    private User $user;

    public function __construct(
        User $user
    ) {
        $this->setUser($user);
    }

    public function validateRegister()
    {
        $input = self::newInput();
        $input->params['email'] = $this->user->getEmail();
        $input->params['password'] = $this->user->getPassword()->getPlainPassword();
        $pipeline = $this->useMiddleware([
            new EmailIsRFC2821(), new PasswordIsSafe()
        ]);
        $output = $pipeline->handle($input);
        return $output;
    }

    public function validateRestoreFromSource()
    {
        $input = self::newInput();
        $input->params['email'] = $this->user->getEmail();
        // パスワードハッシュ化されているパスワードは、バリデーションができないためチェック不可。
        $pipeline = $this->useMiddleware([
            new EmailIsRFC2821(),
        ]);
        $output = $pipeline->handle($input);
        return $output;
    }

    protected function setUser(User $user)
    {
        $this->user = $user;
    }
}
