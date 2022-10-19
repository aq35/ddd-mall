<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\AsyncEventEmitter;

// ./vendor/bin/phpunit DesignPattern/Tests/OrderAsyncTest.php
final class OrderAsyncTest extends TestCase
{
    public function test_EventEmitter_async_emitters(): void
    {
        $asyncEmitter = new AsyncEventEmitter();
        try {
            $this->eventDriven($asyncEmitter);
            $asyncEmitter->getQueue()->start();
            echo "aa" . "\n";
        } catch (\Exception $e) {
            echo "失敗" . "\n";
        }
        $asyncEmitter->getQueue()->stop();
        echo "テスト" . "\n";
        $this->assertTrue(true);
    }

    public function eventDriven(AsyncEventEmitter $asyncEmitter): void
    {
        $i = 0;
        echo "\n";
        echo "非同期処理";
        echo "\n";

        $i += 1;
        $asyncEmitter->getQueue()->onStart(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStart \e[m" . "\n";
        });
        // -------------------------------------------------
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
        });
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $i += 1;
        $asyncEmitter->on('event', function () use ($i, $asyncEmitter) {
            echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
        });
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $asyncEmitter->emit('event');

        $i += 1;
        $asyncEmitter->getQueue()->onAfterTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onAfterTick \e[m" . "\n";
            $asyncEmitter->getQueue()->stop();
        });
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $i += 1;
        $asyncEmitter->getQueue()->onStop(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStop \e[m" . "\n";
        });
    }
}
