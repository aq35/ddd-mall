<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\AsyncEventEmitter;

// ./vendor/bin/phpunit DesignPattern/Tests/AsyncEventEmitterTest.php
final class AsyncEventEmitterTest extends TestCase
{
    public function testEventEmitter_async_emitters(): void
    {

        $i = 0;
        echo "\n";
        echo "非同期処理";
        echo "\n";
        $asyncEmitter = new AsyncEventEmitter();

        $i += 1;
        $asyncEmitter->getQueue()->onStart(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStart \e[m" . "\n";
        });
        $i += 1;
        $asyncEmitter->getQueue()->onStart(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStart \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->onBeforeTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onBeforeTick \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->onBeforeTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onBeforeTick \e[m" . "\n";
        });

        // -------------------------------------------------
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $i += 1;
        $asyncEmitter->on('event', function () use ($i) {
            echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
        });
        $asyncEmitter->emit('event');
        $asyncEmitter->eventListenerForDebug();
        // -------------------------------------------------

        $i += 1;
        $asyncEmitter->getQueue()->onAfterTick(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onAfterTick \e[m" . "\n";
        });

        $i += 1;
        $asyncEmitter->getQueue()->addPeriodicTimer(1e-6, function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] PeriodicTimer \e[m" . "\n";
            $asyncEmitter->getQueue()->stop();
        });

        $i += 1;
        $asyncEmitter->getQueue()->onStop(function () use ($asyncEmitter, $i) {
            echo "\e[31m [$i 番目] onStop \e[m" . "\n";
        });

        // ファイル操作
        $stream = fopen("./DesignPattern/Tests/StreamTextRead.txt", "r");
        $i += 1;
        $asyncEmitter->getQueue()->addReadStream($stream, function () use ($stream, $asyncEmitter, $i) {
            echo $stream . "\n"; // Resource id
            if (filesize("./DesignPattern/Tests/StreamTextRead.txt")) {
                $mes = fread($stream, filesize("./DesignPattern/Tests/StreamTextRead.txt"));
                fclose($stream);
                echo $mes;
                echo "\e[31m [$i 番目] addReadStream \e[m" . "\n";
            }
        });

        // ファイル操作
        $stream = fopen("./DesignPattern/Tests/StreamTextWrite.txt", 'w');
        $i += 1;
        $asyncEmitter->getQueue()->addWriteStream($stream, function () use ($stream, $asyncEmitter, $i) {
            echo $stream . "\n"; // Resource id
            $message = mt_rand() . "\n";
            fwrite($stream, $message); // ファイルに書き込む
            fclose($stream);
            echo "\e[31m [$i 番目] addWriteStream \e[m" . "\n";
        });

        $asyncEmitter->getQueue()->start();
        $this->assertTrue(true);
    }
}
