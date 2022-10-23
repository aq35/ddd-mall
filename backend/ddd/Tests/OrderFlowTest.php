<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

// ./vendor/bin/phpunit ddd/Tests/OrderFlowTest.php
final class OrderFlowTest extends TestCase
{
    public function test_order_flow(): void
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

        // 決済処理のトランザクションデータ作成
        // 支払いリクエスト
        // |=====>|------|------|------|------|
        //        Begin Transaction
        //　
        // |------|<=----|------|------|------|
        //        Create Payment Transaction
        //
        // |------|<=----|------|------|------|
        //        ポイント600円を消費する
        //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された可能性がある
        // |------|=====>|------|------|------|
        //        クレジットカード400円を消費する
        //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された
        //        タイムアウト:ExternalServiceの中で、400円の与信枠が消費されたかどうかわからない
        //        残高不足:InternalServiceAの中で、600円のポイントが消費された
        // |------|===================>|------|
        //        加盟店の売上金に1000円を付与する
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与されたかどうかわからない
        // |------|============>|------|------|
        //        決済の結果通知を送信する
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与された
        //        MessageQueueにイベント送信されたかどうかわからない
        // |------|<=----|------|------|------|
        //　　　　　Commit Transaction
        //        InternalServiceAの中で、600円のポイントが消費された
        //        ExternalServiceの中で、400円の与信枠が消費された
        //        InternalServiceBの中で、加盟店に1000円付与された
        //        MessageQueueにイベント送信された
        //        DBコミットに失敗すると、PaymentServiceにトランザクションデータなくなり、整合性が崩れる。
        // |<=====|------|------|------|------|
        // 支払いレスポンス
        $this->assertTrue(true);
    }
}
