<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

//
use DDD\Tests\Order\ExternalService;
use DDD\Tests\Order\InternalServiceA;
use DDD\Tests\Order\InternalServiceB;
use DDD\Tests\Order\MessageQueue;
use DDD\Tests\Order\PaymentService;
use DesignPattern\Event\ForClient\AsyncEventEmitter;

// ./vendor/bin/phpunit ddd/Tests/ManageOrderTest.php
final class ManageOrderTest extends TestCase
{
    public function test_orderFlow(): void
    {
        // 販売管理アプリケーション
        //
        // 受注　

        // 買い手が注文をし、売り手が同意することで成り立ちます。
        // 法律的には、「売買契約の成立」です。
        // 買い手は外部の人である.売り手は内部の人である。
        // 将来についての約束である <=> 受注により売り手は商品の出荷を約束し、買い手は、代金の支払いを約束する。
        // 買い手と売り手のの間に成立した約束を適切に記録し、実行を追跡し、約束通りに完了させることが販売管理アプリケーションの基本目的です。

        // 何でも約束して良いわけでない
        // 受注は約束です。ビジネスを進める上でできないことを約束したり、自社の不利益になることを約束してはいけません。
        // 受注ということが発生したら、内容が妥当であるか確認しなければなりません。

        // 在庫はあるか、出荷可能か
        // 与信限度額を超えていないか
        // 自社の販売方針に違反していないか
        // 相手との事前の決め事に違反していないか(取引基本契約)

        // 等々の妥当性を確認するために、注文数量や受注金額についての判断/加工/計算の業務ロジックが必要です。
        // このデータとロジックの置き所をドメインオブジェクトと言います。

        // 受注の妥当性の複雑さについての業務ルールは以下のような複雑さを持っています。
        // ・約束の相手が誰かによって、約束して良い範囲が異なる
        // ・どの商品についての約束かによって、金額や納期が異なる
        // ・どのタイミングの約束かによって、金額や納期が異なる

        // 受注からデータとロジックの組み合わせを作成する
        // 受注の数量に関するロジック
        // 数量クラス
        // 数量が個数単位と箱単位での扱いが必要なので、数量単位クラス

        // 出荷
        // 請求
        // 入金
        $this->assertTrue(true);
    }
}
