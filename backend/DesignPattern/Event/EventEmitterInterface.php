<?php

namespace DesignPattern\Event;

interface EventEmitterInterface
{

    /**
     * ---------------------------------------------------------------------------------------------------------------------
     * DESCRIPTION | 説明
     * ---------------------------------------------------------------------------------------------------------------------
     * EventEmitterは、EventEmitterのモード, イベントのリスナーの制御, イベントの制御を行います。
     *
     * EventEmitterのモードとは、
     *
     * イベントとは、
     * イベント = (イベント名,イベント処理)
     *
     * イベントリスナー(イベントを聞く人)とは、
     * イベントリスナー = イベント名があれば、イベント処理を実行する
     *
     * イベントエミッター(イベントを放出するもの)とは、
     * イベントエミッター = イベント名、イベント処理を登録する
     *
     */

    /**
     * Set mode for EventEmitter behaviour.
     *
     * Set mode for EventEmitter behaviour. $emitterMode can be one of:
     * EventEmitter::EVENTS_FORWARD すべてのイベントの転送を許可します | Allows all events to be forwarded (Default)
     * EventEmitter::EVENTS_DISCARD Disallows すべてのイベントの転送を禁止します | all events from being forwarded
     * EventEmitter::EVENTS_DISCARD_INCOMING エミッターにアタッチされたリスナーのみを破棄します | Discards only listeners attached to $this emitter
     * EventEmitter::EVENTS_DISCARD_OUTCOMING フォワーダーでさらに発行するものだけを破棄します | Discards only further emits on forwarder
     *
     * @param int $emitterMode
     */
    public function setMode($emitterMode);

    /**
     * EventEmitter 動作のモードを返します | Returns mode of EventEmitter behaviour.
     *
     * @see setMode
     * @return int
     */
    public function getMode();

    /**
     * (イベントリスナー)イベントのリスナーを設定するならこの関数を
     * このメソッドは EventListener を返します
     * Set listener for event. This method returns EventListener.
     *
     * @param string $event
     * @param callable $listener
     * @return EventListener
     */
    public function on($event, callable $listener);

    /**
     * (イベントリスナー)一度だけ発火するイベントのリスナーを設定するならこの関数を
     * このメソッドは EventListener を返します
     * Set listener for event that will fire only once. This method returns EventListener
     *
     * @param string $event
     * @param callable $listener
     * @return EventListener
     */
    public function once($event, callable $listener);

    /**
     * (イベントリスナー)イベントのリスナーを設定するならこの関数を
     * イベントは、最大で設定された limit と同じ回数だけ発生します
     * このメソッドは EventListener を返します
     * Set listener for event that will fire at most as many times as set limit to. This method returns EventListener
     *
     * @param string $event
     * @param int $limit
     * @param callable $listener
     * @return EventListener
     */
    public function times($event, $limit, callable $listener);

    /**
     * (イベントリスナー)イベントのリスナーを設定するならこの関数を
     * イベントは、$ticks 個のイベントが発行された後に呼び出します
     * このメソッドは EventListener を返します。
     * Set listener for event that will start to be invoked after $ticks number of events is emitted. This method returns EventListener.
     *
     * @param string $event
     * @param int $ticks
     * @param callable $listener
     * @return EventListener
     */
    public function delay($event, $ticks, callable $listener);

    /**
     * (イベントリスナー)イベントのリスナーを設定するならこの関数を
     * イベントは、$ticks 個のイベントが発生した後に 1 回だけ発生します
     * このメソッドは EventListener を返します
     * Set listener for event that will fire only once after $ticks number of events is emitted. This method returns EventListener
     *
     * @param string $event
     * @param int $ticks
     * @param callable $listener
     * @return EventListener
     */
    public function delayOnce($event, $ticks, callable $listener);

    /**
     * (イベントリスナー)イベントのリスナーを設定するならこの関数を
     * イベントは、最大で $limit に設定された回数だけ発火しますが、 $ticks 数のイベントが、発生した後にだけ、発生します。
     * このメソッドは EventListener を返します
     * Set listener for event that will fire at most as many times as $limit is set to, but only after $ticks number of events is emitted.
     * This method returns EventListener
     *
     * @param string $event
     * @param int $limit
     * @param callable $listener
     * @return EventListener
     */
    public function delayTimes($event, $ticks, $limit, callable $listener);

    /**
     * (イベントリスナー)リスナーからイベントを削除したいならこの関数を
     * Remove existing listener for event.
     *
     * @param string $event
     * @param callable $listener
     */
    public function removeListener($event, callable $listener);

    /**
     * (イベントリスナー)リスナーからイベントを全て削除したいならこの関数を
     * Remove all listeners for event.
     *
     * @param string $event
     */
    public function removeListeners($event);

    /**
     * (イベントリスナー)リスナーを全て削除したいならこの関数を
     * Remove all listeners.
     */
    public function flushListeners();

    /**
     * イベントからリスナーを探します
     * Find listener for event.
     * イベントからリスナーを探します.リスナーが見つかった場合は 0 以上の int を返し、見つからなかった場合は null を返します。
     * Find listener for event. Returns int greater or equal 0 if listener is found or null if not.
     *
     * @param string $event
     * @param callable $listener
     * @return int|null
     */
    public function findListener($event, callable $listener);

    /**
     *　
     * (イベントエミッター)指定された引数でイベントを発行します
     * Emit event with specified arguments.
     *
     * @param string $event
     * @param mixed[] $arguments
     */
    public function emit($event, $arguments = []);

    /**
     * (イベントエミッター)イベントを別のエミッターに転送します
     * Forward event to another emitter.
     *
     * @param EventEmitterInterface $emitter
     * @param string $event
     * @return EventListener
     */
    public function copyEvent(EventEmitterInterface $emitter, $event);

    /**
     *
     * (イベントエミッター)イベントのセットを別のエミッターに転送します。
     * Forward set of events to another emitter.
     *
     * @param EventEmitterInterface $emitter
     * @param string[] $events
     * @return EventListener[]
     */
    public function copyEvents(EventEmitterInterface $emitter, $events);

    /**
     * (イベントエミッター)すべてのイベントを別のエミッターに転送します。
     * Forward all events to another emitter.
     *
     * @param EventEmitterInterface $emitter
     * @return EventEmitterInterface
     */
    public function forwardEvents(EventEmitterInterface $emitter);

    /**
     * (イベントエミッター)以前に別のエミッターに転送されたイベントを破棄します。
     * Discard events previously forwarded to another emitter.
     *
     * @param EventEmitterInterface $emitter
     * @return EventEmitterInterface
     */
    public function discardEvents(EventEmitterInterface $emitter);
}
