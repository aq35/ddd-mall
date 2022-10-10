<?php

namespace DDD\Handler;

use DesignPattern\Middleware\HandleMiddlewareForClient\PipelineBuilder;

abstract class Validator
{
    // public abstract function validate();

    public function makeValidate($ruleHandlers)
    {
        $pipelineBuilder = (new PipelineBuilder);
        foreach ($ruleHandlers as $ruleHandler) {
            $pipelineBuilder = $pipelineBuilder->use($ruleHandler);
        }
        $pipeline = $pipelineBuilder->build(new ValidationHandler()); // Handler登録
        return $pipeline;
    }
}

// 以下の代わりに作っている
// $pipeline = (new PipelineBuilder);
// ->use(new EmailIsRFC2821()) // EmailがRFCであるか
// ->use(new PasswordIsSafe()) // パスワードが安全であるか
// ->build(new ValidationHandler()); // Handler登録
