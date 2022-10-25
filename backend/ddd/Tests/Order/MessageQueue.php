<?php

namespace DDD\Tests\Order;

final class MessageQueue
{
    // Phase4
    // |------|===================>|------|
    //        加盟店の売上金に1000円を付与する
    //        InternalServiceAの中で、600円のポイントが消費された
    //        ExternalServiceの中で、400円の与信枠が消費された
    //        InternalServiceBの中で、加盟店に1000円付与されたかどうかわからない
    // |------|<=----|------|------|------|
    //        Save Results
    public static function publishEvent(): void
    {
        echo "publishEvent" . "\n";
    }
}
