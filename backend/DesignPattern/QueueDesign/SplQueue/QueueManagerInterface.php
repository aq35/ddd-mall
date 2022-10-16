<?php

namespace DesignPattern\QueueDesign\SplQueue;

use DesignPattern\QueueDesign\QueueHasTimer\Timer\TimerInterface;

// [ライフサイクル] QueueManagerInterface QueueDesign の状態管理を担当します。
// QueueManagerInterface は、Queueサービス の操作系を持ちません。
interface QueueManagerInterface
{
    /**
     * ループが現在実行されているかどうかを確認します
     * Check if Queue is currently running.
     *
     * @return bool
     */
    public function isRunning();

    // [PHPリソース]
    // リソースとは
    // リソースはPHPと外部世界とのやり取りを行うための特別な変数です。
    // 例えばPHPからMySQLやSQLite、テキストファイルなどのデータベースへ接続したり、画像ファイルを開いたりする際、PHPではそれらを実行するための専用の関数が定義されています。
    // そのような関数を実行して取得した接続情報を変数へ格納することが可能で、これらの変数は外部リソースを保持し、型はリソース型となります。
    //
    // リソースの型
    // リソース型 は PHPの外部世界 とやり取りを行うデータが格納されている型 です。
    // つまり、リソースは、特別な関数により作成された外部情報を持つ変数のことです。そのような変数の型がリソース型となるのです。
    // PHPの外部世界とのやり取りを行うためには、様々な処理が必要になりますが、リソースにはそのような処理を完結に扱うためのデータが格納されます。
    //
    // リソースを使うことで...
    // したがって、通常行う複雑な手続きが、リソースを呼び出すだけで一括して処理することが可能となり、外部リソースのやり取りが非常に楽になります。
    // リソースの実体は整数値です。リソースは外部世界とのやり取りを実現するための数値データ（ハンドル）が格納されているハンドラです。
    // このような特殊な型のため、他の型からリソース型への変換はできません。
    //
    // リソースへの参照が無くなった時点でそのリソースは自動的に削除されます。これにより、このリソースが作成した全てのリソースは開放され、同時にメモリも開放されます。
    // 例えばデータベースのMySQLとやり取りを行うために、データベースへ接続するための特別な関数を動かし、結果を変数に格納しておきます。この変数はリソース型の変数となります。
    // データベースを利用しているアプリケーションであれば、頻繁にデータベースを呼び出すことになりますが、その際、リソース型の変数を呼び出すだけでいつでもデータベースとやり取りが行えるようになります。この特別な変数がリソース型の変数です。

    /**
     * [PHPリソース]
     * ストリームの読み取り準備が整ったときに通知を受けるリスナーを登録します
     * Register a listener to be notified when a stream is ready to read.
     *
     * @param resource $stream
     * @param callable $listener
     */
    public function addReadStream($stream, callable $listener);

    /**
     * [PHPリソース]
     * Register a listener to be notified when a stream is ready to write.
     *
     * @param resource $stream
     * @param callable $listener
     */
    public function addWriteStream($stream, callable $listener);

    /**
     * Remove the read event listener for the given stream.
     *
     * @param resource $stream
     */
    public function removeReadStream($stream);

    /**
     * 指定されたストリームの書き込みイベント リスナーを削除します。
     * Remove the write event listener for the given stream.
     *
     * @param resource $stream
     */
    public function removeWriteStream($stream);

    /**
     * 指定されたストリームのすべてのリスナーを削除します。
     * Remove all listeners for the given stream.
     *
     * @param resource $stream
     */
    public function removeStream($stream);

    /**
     * 指定された間隔の後に 1 回呼び出されるコールバックをキューに入れます。
     * Enqueue a callback to be invoked once after the given interval.
     * 同時に実行するようにスケジュールされたタイマーの実行順序は保証されません。
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addTimer($interval, callable $callback);

    /**
     * 指定された間隔の後に繰り返し呼び出されるコールバックをキューに入れます。
     * Enqueue a callback to be invoked repeatedly after the given interval.
     * 同時に実行するようにスケジュールされたタイマーの実行順序は保証されません。
     * The execution order of timers scheduled to execute at the same time is not guaranteed.
     *
     * @param float $interval
     * @param callable $callback
     * @return TimerInterface
     */
    public function addPeriodicTimer($interval, callable $callback);

    /**
     * 保留中のタイマーをキャンセルします。
     * Cancel a pending timer.
     *
     * @param TimerInterface $timer
     */
    public function cancelTimer(TimerInterface $timer);

    /**
     * 指定されたタイマーがアクティブかどうかを確認します。
     * Check if a given timer is active.
     *
     * @param TimerInterface $timer
     * @return bool
     */
    public function isTimerActive(TimerInterface $timer);

    /**
     * イベントループの開始ティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the start tick of event Queue.
     * コールバックは、何よりも先に、キューに入れられた順序で実行されることが保証されています。
     * Callbacks are guarenteed to be executed in the order they are enqueued, before anything else.
     *
     * @param callable $listener
     */
    public function onStart(callable $listener);

    /**
     * イベント ループの停止ティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the stop tick of event Queue.
     * コールバックは、エンキューされた順序で実行されることが保証されています。
     * Callbacks are guarenteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onStop(callable $listener);

    /**
     * イベント ループの次のティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on the next tick of the event Queue.
     * コールバックは、タイマーまたはストリーム イベントの前に、キューに入れられた順序で実行されることが保証されます。
     * Callbacks are guaranteed to be executed in the order they are enqueued, before any timer or stream events.
     *
     * @param callable $listener
     */
    public function onBeforeTick(callable $listener);

    /**
     * イベント ループの将来のティックでコールバックが呼び出されるようにスケジュールします。
     * Schedule a callback to be invoked on a future tick of the event Queue.
     * コールバックは、エンキューされた順序で実行されることが保証されています。
     * Callbacks are guaranteed to be executed in the order they are enqueued.
     *
     * @param callable $listener
     */
    public function onAfterTick(callable $listener);

    public function onTick(callable $listener);
}
