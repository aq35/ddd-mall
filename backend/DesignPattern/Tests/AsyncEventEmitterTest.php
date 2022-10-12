<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\EventEmitter;
use DesignPattern\Event\Loop\ForClient\Loop;
use DesignPattern\Event\Loop\ForClient\SelectLoop;

final class AsyncEventEmitterTest extends TestCase
{

    public function testEventEmitter_async_emitters(): void
    {
        $loop = new Loop(new SelectLoop());

        $baseEmitter  = new EventEmitter(); // this is basic, synchronous EventEmitter
        $asyncEmitter = new EventEmitter($loop); // this EventEmitter uses loop to dispatch events asynchronously

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

        $loop->onAfterTick(function () use ($loop) {
            echo "\n";
            $loop->stop();
        });

        $loop->start();
        $this->assertTrue(true);
    }
}
