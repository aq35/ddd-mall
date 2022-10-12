<?php

namespace DDD\Validator;

use DesignPattern\Middleware\ForClient\PipelineBuilder;
use DesignPattern\Middleware\Domain\Input;

abstract class Validator
{
    // Validatorの形式は、本クラスで提供する
    // Inputは、ドメイン要素なので、あまり好き勝手操作されたくない。
    protected static function newInput()
    {
        $input = new Input();
        $input->params = [];
        $input->errors = [];
        return new Input();
    }

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
