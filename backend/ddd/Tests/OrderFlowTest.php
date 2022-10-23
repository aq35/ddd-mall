<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

// ./vendor/bin/phpunit ddd/Tests/OrderFlowTest.php
final class OrderFlowTest extends TestCase
{
    // リクエストレイヤー
    // ●------|------|------|------|------|
    //        PaymentService
    // |------●------|------|------|------|
    //               InternalServiceA
    // |------|------●------|------|------|
    //                      InternalServiceB
    // |------|------|------●------|------|
    //                             ExternalService
    // |------|------|------|------●------|
    //                                    MessageQueue
    // |------|------|------|------|------●

    public function test_orderFlow(): void
    {

        // 決済処理のトランザクションデータ作成
        // Phase1
        // 支払いリクエスト
        // |=====>|------|------|------|------|
        //        Begin Transaction
        //　
        // |------|<=----|------|------|------|
        //        Create Payment Transaction
        //
        // |------|<=----|------|------|------|
        //        Commit Transaction
        PaymentService::createPaymentTransaction();

        // Phase2
        // |------|=====>|------|------|------|
        //        ポイント600円を消費する
        //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された可能性がある
        //
        // |------|<=----|------|------|------|
        //        Save Results
        InternalServiceA::consumeUserPoint();

        // Phase3
        // |------|======|======|=====>|------|
        //        クレジットカード400円を消費する
        //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された
        //        タイムアウト:ExternalServiceの中で、400円の与信枠が消費されたかどうかわからない
        //        残高不足:InternalServiceAの中で、600円のポイントが消費された
        //
        // |------|<=----|------|------|------|
        //        Save Results
        ExternalService::consumeUserCreditCard();

        // Phase4
        // |------|===================>|------|
        //        加盟店の売上金に1000円を付与する
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与されたかどうかわからない
        // |------|<=----|------|------|------|
        //        Save Results
        InternalServiceB::addPartnerSales();

        // Phase5
        // |------|============>|------|------|
        //        決済の結果通知を送信する
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与された
        //        MessageQueueにイベント送信されたかどうかわからない
        // |------|<=----|------|------|------|
        //        Save Results
        MessageQueue::publishEvent();

        // Phase6
        // |------|<=----|------|------|------|
        //        Begin Transaction
        // |------|<=----|------|------|------|
        //　　　　　Commit Transaction
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与された
        //        MessageQueueにイベント送信された
        //        DBコミットに失敗すると、PaymentServiceにトランザクションデータなくなり、整合性が崩れる。
        // |------|<=----|------|------|------|
        //        Commit Transaction
        PaymentService::updatePaymentTransaction();

        // |<=====|------|------|------|------|
        // 支払いレスポンス

        $this->assertTrue(true);
    }

    // 冪等性
    // 決済処理中リトライ処理が発生した場合、何も考慮しない場合、依存先サービスに投げる操作が多重に実行されたらお客様の残高が何度も引かれてしまう問題が発生します。
    //
    // このような操作、多重に実行しても結果（残高が一度のみ引かれる）が変わることがない特性のことを、冪等性と呼びます。
    //
    // 呼び出される側の操作自体を冪等性が担保される前提で作って、何度も実行しても同じ結果になる
    //
    // メルペイでは、基本的に各サービスが冪等性のあるAPIを提供することが強く要求されています。
    //
    // APIの提供側は、リクエストのパラメータとしてIdempotency Keyを必ず受け取って冪等性担保できるように実装する
    // APIの利用側は、多重処理されないようにユニークなIdempotency Keyを生成してAPIを叩く
    //
    //
    // |=====>|------|------|------|------|
    // 支払いリクエスト
    //　
    // |------|=====>|------|------|------|
    //        ポイント600円を消費する
    //        (Idempotency_Key:AAA)
    //
    // |------|<=====|------|------|------|
    //        途中で、X Timeout
    //
    // |------|------|<=----|------|------| Consume 600 point successfully (600ポイント消費に成功)
    //　　　　　　　　　　　　　　　　　　　　　　　AAAを永続化
    // |------|=====>|------|------|------|
    //        (Retry)ポイント600円を消費する
    //        (Idempotency_Key:AAA)
    //
    // |------|------|<=----|------|------| AAAが既にあるため、ポイント消費をしない
    //                                      Return result directly without consume point again (ポイント消費なし)
    //
    // |------|<=====|------|------|------|
    //         Success
    //
    // |------|<=----|------|------|------| Save Results
    //
    // |<=====|------|------|------|------|
    // OK

    // PaymentServiceの場合、決済処理を受け付けたときに内部のトランザクションIDを一度確定してからお客様の残高を減らす操作しています。
    // そして、残高を減らすときに、依存先のサービスが提供する残高操作API（冪等性が担保されている）に確定された内部のトランザクションIDをIdempotency Keyとして渡せば、
    // 何度も実行しても残高が一度だけ引かれることが保証されます。(InternalServiceAは、既にあれば残高を減らさない)

    // 補償トランザクション
    // 決済処理の途中で、どうしても前に進められない場合、今まで実行してきた操作を一度きちんとロールバックしてから失敗を返す必要があります。
    // そのための処理が補償トランザクションと呼ばれています。
    //
    // 例えばはじめに書いた例だと、内部サービス（InternalServiceA）が管理されるポイントの消費が確定された後、
    // 外部サービス（ExternalService）クレジットカードでの決済が失敗したら、そのままで処理終了して失敗を返すと、
    // お客様にとって決済が失敗したのに、ポイントが消費された状態になってしまって不整合が発生します。

    // -----------------------------------------
    //         -> Failed
    // Created -> Paid  -> Refunded
    // -----------------------------------------
    // Created: 決済受付
    // Paid: 支払い成功した
    // Failed: 支払い失敗した
    // Refunded: 返金がある場合、返金された


    // PaymentServiceでは、正常のトランザクション処理と共に、ロールバックするための補償トランザクション処理もステートマシンで管理して、
    // 必要に応じて実行して決済処理の整合性を担保しています。
    // -----------------------------------------
    // Created -> Save Paymenet Transaction　　　　             |  Update Payment Transaction Status ----> Failed
    //                                                  　     | (Retry)
    //         　　↓↓                                   　　    |                     ↑↑
    //            Consume User Balance  ---- rollback ---->　  | Rollback User Balance Consumption
    //            (Retry)                                 　   | (Retry)
    //         　　↓↓                                     　　  |                     ↑↑
    //            Consume User CreditCard ---- rollback ---->　|  Rollback User CreditCard Consumption
    //            (Retry)                                 　   | (Retry)
    //         　　↓↓                                      　　 |--------------------------Compensating Transaction 補償トランザクション
    //            Add Partner Balance
    //            (Retry)
    //         　　↓↓
    //            Publish Event
    //            (Retry)
    //         　　↓↓
    //            Update Payment Transaction Status =======> Succceded
    //            (Retry)
    // -----------------------------------------
    // Created: 決済受付
    // Paid: 支払い成功した
    // Failed: 支払い失敗した
    // Refunded: 返金がある場合、返金された

    // 残高消費の処理には金額の消費と履歴の記録処理があった場合、一つの操作にまとめると、ロールバック処理が実行された後に金額は正しく返せますが、履歴が汚れる問題
    // 仮押さえの段階では、残高を仮売上状態として押さえるだけ
    // 実行したら実売上と履歴の記録処理が走る
    // 仮押さえの後にロールバック処理を走ったら、履歴が汚れるなどの副作用も抑えながらよりきれいな補償処理が実現できます。
}
