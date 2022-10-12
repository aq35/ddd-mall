<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\EventEmitter;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION | 説明
 * ---------------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE | 使い方
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * $> ./vendor/bin/phpunit DesignPattern
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */

final class EventEmitterTest extends TestCase
{
    /*---------------------------------------------------------------------------------------------------------------------
     * EventEmitterが起動できるか
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testEventEmitterQuickstart(): void
    {
        $emitter = new EventEmitter();

        $emitter->on('script.start', function ($user, $time, $asset) {
            echo "\n";
            echo "$user has started this script at $time.\n";
            echo "$user は、EventEmitter を起動しました。 開始日時 $time.\n";
            $this->assertTrue($asset);
        });

        $emitter->emit('script.start', [get_current_user(), date('Y-m-d H:i:s'), true]);
    }
}
