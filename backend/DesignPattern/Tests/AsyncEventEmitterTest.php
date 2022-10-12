<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\EventEmitter;
use DesignPattern\Event\Domain\EventEmitterMode;
use DesignPattern\Event\Loop\Loop;

final class AsyncEventEmitterTest extends TestCase
{

    public function testEventEmitter_async_emitters(): void
    {
        $loop = new Loop(new SelectLoop());

        $baseEmitter  = new EventEmitter(); // this is basic, synchronous EventEmitter
        $asyncEmitter = new EventEmitter($loop); // this EventEmitter uses loop to dispatch events asynchronously

        $baseEmitter->on('event', function () {
            echo "> \$baseEmitter received event!\n";
        });
        $asyncEmitter->on('event', function () {
            echo "> \$asyncEmitter received event!\n";
        });

        echo "\nThis event is sync so it will\n";
        $baseEmitter->emit('event');
        echo "resolve listeners in the middle of printing this string\n";

        echo "\nThis event is async so it will\n";
        $asyncEmitter->emit('event');
        echo "resolve listeners in the next cycle of loop, after string has been printed\n";

        $loop->onAfterTick(function () use ($loop) {
            echo "\n";
            $loop->stop();
        });
        $loop->start();
    }
}
