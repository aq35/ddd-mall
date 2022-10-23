<?php

namespace DDD\Tests;

final class ExternalService
{
    // Phase3
    // |------|======|======|=====>|------|
    //        クレジットカード400円を消費する
    //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された
    //        タイムアウト:ExternalServiceの中で、400円の与信枠が消費されたかどうかわからない
    //        残高不足:InternalServiceAの中で、600円のポイントが消費された
    //
    // |------|<=----|------|------|------|
    //        Save Results
    public static function consumeUserCreditCard(): void
    {
    }
}
