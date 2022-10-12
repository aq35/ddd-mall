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
     * EventEmitter on()
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testEventEmitter_On(): void
    {
        $emitter = new EventEmitter();

        $emitter->on('script.start', function ($asset) {
            echo "\n";
            echo "イベントが実行されました.";
            $this->assertTrue($asset);
        });
        echo "\n------1つ、リスナーを作りました。イベントをEmitします。実行されるはずです。------\n";
        $emitter->emit('script.start', [true]);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * EventEmitter on() を何回も
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testEventEmitter_On_Multiple_Listeners(): void
    {
        $emitter = new EventEmitter();
        $count = 0;

        $emitter->on('script.start', function (&$count) {
            $count += 1;
            echo "\n";
            echo "$count つ目のイベントが実行されました。";
        });
        $emitter->on('script.start', function (&$count) {
            $count += 1;
            echo "\n";
            echo "$count つ目のイベントが実行されました。";
        });
        $emitter->on('script.start', function ($count) {
            $count += 1;
            echo "\n";
            echo "$count つ目のイベントが実行されました。";
            $this->assertTrue($count == 3);
        });

        echo "\n------3つ、リスナーを作りました。イベントをEmitします。全部、実行されるはずです。------\n";
        $emitter->emit('script.start', [&$count]);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * EventEmitter delayTimes() 順番飛ばし (応用)
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testEventEmitter_delayTimes(): void
    {
        $emitter = new EventEmitter();

        // 0,1,2は飛ばして、3,4 を実行する
        $emitter->delayTimes('event.number', 3, 2, function ($number) {
            echo "\n";
            echo " \$number=$number.\n";
        });

        $counter = 0;
        $counterMax = 5;

        echo "\n------0,1,2は飛ばして、3,4 を実行するリスナーを作りました。イベントをEmitします------\n";
        while ($counter < $counterMax) {
            $emitter->emit('event.number', [++$counter]);
        }

        $this->assertTrue(true);
    }

    /*---------------------------------------------------------------------------------------------------------------------
     * EventEmitter delayTimes() 3回のみ実行 (応用)
     * ---------------------------------------------------------------------------------------------------------------------*/
    public function testEventEmitter_times(): void
    {
        $emitter = new EventEmitter();

        // 最初のemitから3回、以降は破棄
        $emitter->times('event.number', 3, function ($number) {
            echo "\n";
            echo "最初のemitから3回、以降は破棄 \$number=$number.\n";
        });

        $counter = 0;
        $counterMax = 5;

        echo "\n------最初のemitから3回を実行するリスナーを作りました。イベントをEmitします------\n";
        while ($counter < $counterMax) {
            $emitter->emit('event.number', [++$counter]);
        }

        $this->assertTrue(true);
    }

    public function testEventEmitter_disposable(): void
    {
        $emitter = new EventEmitter();

        // this handler will be disposed after first fire
        $emitter->once('event.number', function ($number) {
            echo "\n";
            echo "Event has been fired with \$number=$number.\n";
        });

        $counter = 0;
        echo "\n------ これから3回、Emitしますが、1度のみ処理されるはずです。------\n";
        $emitter->emit('event.number', [++$counter]);
        $emitter->emit('event.number', [++$counter]); // 実行されないはず
        $emitter->emit('event.number', [++$counter]); // 実行されないはず

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }


    public function testEventEmitter_delayed(): void
    {
        $emitter = new EventEmitter();

        // 3回目からスタート
        // this handler will start to work on 3rd time it receives this particular event
        $emitter->delay('event.number', 3, function ($number) {
            echo "\n";
            echo "Event has been fired with \$number=$number.\n"; // ３,4,5が表示されているはず
        });

        $counter = 0;
        $counterMax = 5;

        echo "\n------ ３,4,5実行されるEmitします　------\n";
        while ($counter < $counterMax) {
            $emitter->emit('event.number', [++$counter]);
        }

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }

    public function testEventEmitter_cancelling(): void
    {
        $emitter = new EventEmitter();

        $listener1 = $emitter->on('event', $callback1 = function () {
            echo "1st listener reacted to the event.\n";
        });
        $listener2 = $emitter->on('event', $callback2 = function () {
            echo "2nd listener reacted to the event.\n";
        });
        $listener3 = $emitter->on('event', $callback3 = function () {
            echo "3rd listener reacted to the event.\n";
        });

        echo "\n------3つリスナーを作りました。イベントをEmitします。全部実行されるはずです。------\n";
        $emitter->emit('event');

        echo "\n------2をキャンセルしました。イベントをEmitします。2は実行されないはずです。------\n";
        $listener2->cancel(); // preferred method to cancel listener
        $emitter->emit('event');

        echo "\n------ イベントから、コールバック3を削除しました。イベントは 1st だけ実行されます。 ------\n";
        $emitter->removeListener('event', $callback3); // alternative method to cancel listener
        $emitter->emit('event');

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }
}
