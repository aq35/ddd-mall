<?php

namespace DDD\Validator;

// DesignPatternは、Validatorクラスまでで、子クラスには見えないようにしたい。

use DesignPattern\Middleware\ForClient\PipelineBuilder;
use DesignPattern\Middleware\Domain\Input;
use DesignPattern\Middleware\Domain\Output;
use DesignPattern\Middleware\Domain\Handler;

abstract class Validator
{
    public function validate($params, $ruleHandlers): Output
    {
        return $this->buildPipeline($ruleHandlers)->handle(self::newInput($params));
    }

    // Inputクラスのプロパティ
    // Validatorの形式は、本クラスで提供する
    // Inputは、ドメイン要素なので、あまり好き勝手操作されたくない。
    // $params には、 key , value の連想配列をセットしてください。
    private static function newInput(array $params): Input
    {
        $input = new Input();
        $input->params = $params;
        $input->errors = [];
        return $input;
    }

    // ミドルウェアパターンのPipelineBuilderの組み立て
    private function buildPipeline($ruleHandlers): Handler
    {
        $pipelineBuilder = (new PipelineBuilder);
        foreach ($ruleHandlers as $ruleHandler) {
            $pipelineBuilder = $pipelineBuilder->use($ruleHandler);
        }
        $pipeline = $pipelineBuilder->build(new ValidationHandler()); // Handler登録
        return $pipeline;
    }
}
