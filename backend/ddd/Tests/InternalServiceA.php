<?php

namespace DDD\Tests;

final class InternalServiceA
{
    // Phase2
    // |------|<=----|------|------|------|
    //        ポイント600円を消費する
    //        タイムアウト:InternalServiceAの中で、600円のポイントが消費された可能性がある
    //
    // |------|<=----|------|------|------|
    //        Save Results
    public static function consumeUserPoint(): void
    {
        echo "consumeUserPoint" . "\n";
    }
}
