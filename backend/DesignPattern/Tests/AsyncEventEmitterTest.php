<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\SeriesEventEmitter;
use DesignPattern\Event\ForClient\AsyncEventEmitter;

final class AsyncEventEmitterTest extends TestCase
{
    // public function testEventEmitter_async_emitters(): void
    // {
    //     $baseEmitter  = new SeriesEventEmitter();
    //     $asyncEmitter = new AsyncEventEmitter();

    //     $baseEmitter->on('event', function () {
    //         echo "> 同期処理を完了します \n";
    //         echo "> \$baseEmitter received event!\n";
    //     });
    //     $asyncEmitter->on('event', function () {
    //         echo "> 非同期処理を完了します \n";
    //         echo "> \$asyncEmitter received event!\n";
    //     });

    //     echo "\n このイベントは同期的に処理をします。 \n";
    //     $baseEmitter->emit('event');
    //     echo "同期処理が終わっている想定です。\n";

    //     echo "\nこのイベントは非同期的に処理をします。\n";
    //     $asyncEmitter->emit('event');
    //     echo "非同期処理は、以降に表示されます。\n";

    //     $asyncEmitter->getQueue()->onAfterTick(function () use ($asyncEmitter) {
    //         echo "\n";
    //         $asyncEmitter->getQueue()->stop();
    //     });

    //     $asyncEmitter->getQueue()->start();
    //     $this->assertTrue(true);
    // }

    public function testEventEmitter_async_emitters(): void
    {
        $i = 0;
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $asyncEmitter = new AsyncEventEmitter();

        $asyncEmitter->eventListenerForDebug();

        $i += 1;
        $asyncEmitter->getQueue()->onBeforeTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 onBeforeTick \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->onStart(function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 onStart \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m $i 番目 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m $i 番目 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m $i 番目 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $asyncEmitter->emit('event');

        $i += 1;
        $asyncEmitter->getQueue()->onAfterTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 onAfterTick \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->onTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 onTick \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->addPeriodicTimer(1e-6, function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 PeriodicTimer \e[m" . "\n";
            $asyncEmitter->getQueue()->stop();
        });

        $i += 1;
        $asyncEmitter->getQueue()->onStop(function () use ($asyncEmitter, $i) {
            echo "\e[31m $i 番目 onStop \e[m" . "\n";
        });

        $asyncEmitter->getQueue()->start();
        $this->assertTrue(true);
    }
}
