<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\AsyncEventEmitter;

// ./vendor/bin/phpunit DesignPattern/Tests/OrderAsyncTest.php
final class OrderAsyncTest extends TestCase
{
    public function testEventEmitter_async_emitters(): void
    {

        $i = 0;
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $asyncEmitter = new AsyncEventEmitter();

        $i += 1;
        $asyncEmitter->getQueue()->onStart(function () use ($i) {
            echo "処理を開始します。" . "\n";
        });

        // -------------------------------------------------
        $i += 1;
        $asyncEmitter->on('step1', function () use ($i) {
            echo "step1" . "\n";
            echo "注文の作成" . "\n";
        });
        $i += 1;
        $asyncEmitter->on('step2', function () use ($asyncEmitter, $i) {
            echo "顧客にメール通知" . "\n";
            echo "決済の適用" . "\n";
            echo "在庫引当" . "\n";
            $asyncEmitter->emit('step3');
        });

        $asyncEmitter->eventListenerForDebug();
        // -------------------------------------------------

        $i += 1;
        $asyncEmitter->getQueue()->onStop(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStop \e[m" . "\n";
        });
        $asyncEmitter->emit('step1');
        $asyncEmitter->getQueue()->start();
        $this->assertTrue(true);
    }
}
