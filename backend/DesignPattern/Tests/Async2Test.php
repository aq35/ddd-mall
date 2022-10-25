<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DesignPattern\Async2\Internal\GeneratorContainer;
use Exception;

// ./vendor/bin/phpunit DesignPattern/Tests/Async2Test.php
final class Async2Test extends TestCase
{
    public function test_async2(): void
    {

        $generatorContainer =  new GeneratorContainer($this->generatorForTest(1), 'key');

        $current = $generatorContainer->current(); // yield にセットされている値を取得します。
        $e = null;
        try {
            $validCurrent = $generatorContainer->currentOrFail();
        } catch (\BadMethodCallException $e) {
            $e = $e;
        }

        $key = $generatorContainer->key();
        $isWorking = $generatorContainer->isWorkingGenerator();
        $hashId = $generatorContainer->hashId();
        $yieldKey = $generatorContainer->getYieldKey();
        $generatorContainer->send('aaaaa');
        $generatorContainer->send('bbbbb');
        $generatorContainer->send('ccccc');

        dd($current, $e->getMessage(), $isWorking, $key, $yieldKey, $hashId);

        $this->assertTrue(true);
    }


    public function generatorForTest($key)
    {
        yield $key;

        while (true) {
            $n = yield;
            echo $n . PHP_EOL;
        }
    }
}
