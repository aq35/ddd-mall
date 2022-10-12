<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\EventEmitter;
use DesignPattern\Event\Domain\EventEmitterMode;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION | 説明
 * ---------------------------------------------------------------------------------------------------------------------
 * EventEmitter単体の動作テスト
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

        echo "\n------ 3,4,5実行されるEmitします------\n";
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

    public function testEventEmitter_switching_modes(): void
    {
        $source = new EventEmitter();
        $bridge = new EventEmitter();
        $target = new EventEmitter();

        // the handlers are being attached to $target emitter.
        $source->on('event', function () {
            echo "> \$source EventEmtiter got event!\n";
        });
        $bridge->on('event', function () {
            echo "> \$bridge EventEmtiter got event!\n";
        });
        $target->on('event', function () {
            echo "> \$target EventEmtiter got event!\n";
        });

        // create forwarding chaing
        $source->forwardEvents($bridge);
        $bridge->forwardEvents($target);

        // emit events using different modes
        echo "\n";
        echo "#----------- #1 MODE=EVENTS_FORWARD (DEFAULT) -----------\n";
        echo "# in this mode all events will be received and dispatched further.\n\n";
        $source->emit('event');

        echo "\n";
        echo "#----------- #2 MODE=EVENTS_DISCARD_INCOMING -----------\n";
        echo "# in this mode the incoming events will be dropped but, the propagation will continue.\n\n";
        $bridge->setMode(EventEmitterMode::EVENTS_DISCARD_INCOMING); // $bridgeはアウト
        $source->emit('event');

        echo "\n";
        echo "#----------- #3 MODE=EVENTS_DISCARD_OUTCOMING -----------\n";
        echo "# in this mode the incoming events will be fired, but the propagation will be stopped.\n\n";
        $bridge->setMode(EventEmitterMode::EVENTS_DISCARD_OUTCOMING); // $targetはアウト
        $source->emit('event');

        echo "\n";
        echo "#----------- #4 MODE=EVENTS_DISCARD -----------\n";
        echo "# in this mode both incoming and outcoming events are being dropped.\n\n";
        $bridge->setMode(EventEmitterMode::EVENTS_DISCARD); // $bridgeと$targetはアウト
        $source->emit('event');

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }

    public function testEventEmitter_forwarding_events(): void
    {
        $source = new EventEmitter();
        $target = new EventEmitter();

        // the handlers are being attached to $target emitter.
        $target->on('account.name', function ($name) {
            echo "New account name is $name.\n";
        });
        $target->on('account.pass', function ($pass) {
            echo "New account pass is $pass.\n";
        });

        // this will make all events forward to $target so you don't have to know their exact list.
        // this preferred option instead of copying.
        $source->forwardEvents($target);

        // while the emits are being emited on $source emitter.
        $source->emit('account.name', ['admin']);
        $source->emit('account.pass', ['admin1234']);

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }

    public function testEventEmitter_copying_events(): void
    {
        $source = new EventEmitter();
        $target = new EventEmitter();
        // the handlers are being attached to $target emitter.
        $target->on('account.name', function ($name) {
            echo "New account name is $name.\n";
        });
        $target->on('account.pass', function ($pass) {
            echo "New account pass is $pass.\n";
        });

        $source->copyEvent($target, 'account.name');
        //$source->copyEvent($target, 'account.pass'); // uncomment this to copy also 'account.pass' event!

        // while the emits are being emited on $source emitter.
        $source->emit('account.name', ['admin']); // コピーしたイベントを実行できる
        $source->emit('account.pass', ['admin1234']); // 存在しないので、実行されない

        // 同期なので、delayTimesのコールバックが終わるまで待っている。
        $this->assertTrue(true);
    }
}
