<?php

namespace DDD\Handler;

use DesignPattern\Middleware\HandleMiddlewareForClient\PipelineBuilder;

abstract class Validator
{
    public function useMiddleware($ruleHandlers)
    {
        $pipelineBuilder = (new PipelineBuilder);
        foreach ($ruleHandlers as $ruleHandler) {
            $pipelineBuilder = $pipelineBuilder->use($ruleHandler);
        }
        $pipeline = $pipelineBuilder->build(new ValidationHandler()); // Handler登録
        return $pipeline;
    }
}
