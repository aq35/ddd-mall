<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\_Event\ForClient\SeriesEventEmitter;
use DesignPattern\_Event\ForClient\AsyncEventEmitter;
use DesignPattern\Event\Loop\ForClient\Loop;
use DesignPattern\Event\Loop\ForClient\SelectLoop;

final class AsyncEventEmitterTest extends TestCase
{
    public function testEventEmitter_async_emitters(): void
    {
        $baseEmitter  = new SeriesEventEmitter();
        $asyncEmitter = new AsyncEventEmitter();

        $baseEmitter->on('event', function () {
            echo "> 同期処理を完了します \n";
            echo "> \$baseEmitter received event!\n";
        });
        $asyncEmitter->on('event', function () {
            echo "> 非同期処理を完了します \n";
            echo "> \$asyncEmitter received event!\n";
        });

        echo "\n このイベントは同期的に処理をします。 \n";
        $baseEmitter->emit('event');
        echo "同期処理が終わっている想定です。\n";

        echo "\nこのイベントは非同期的に処理をします。\n";
        $asyncEmitter->emit('event');
        echo "非同期処理は、以降に表示されます。\n";

        $asyncEmitter->getLoop()->onAfterTick(function () use ($asyncEmitter) {
            echo "\n";
            $asyncEmitter->getLoop()->stop();
        });

        $asyncEmitter->getLoop()->start();
        $this->assertTrue(true);
    }
}
