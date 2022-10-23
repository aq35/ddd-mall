<?php

namespace DDD\Tests;

final class PaymentService
{
    // PaymentServiceでは1つの決済トランザクション処理を複数のフェーズに細分化して実行するようにしています。
    // 決済処理を受付時に内部トランザクションデータとIDを必ず一つのフェーズとして確定してから処理する
    // フェーズの進行状態を必ず記録する
    // フェーズの粒度はリトライとロールバック処理のやりやすさによって決める
    // 依存先のサービスに対して1つ操作した場合、基本1つのフェーズとして分けて、操作時のログも細かく残すようにしている

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
    public static function createPaymentTransaction(): void
    {
    }

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
    public static function updatePaymentTransaction(): void
    {
    }
}
