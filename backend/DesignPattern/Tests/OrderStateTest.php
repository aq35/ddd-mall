<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DDD\Order\OrderState\OrderContext;

// ./vendor/bin/phpunit DesignPattern/Tests/OrderStateTest.php
final class OrderStateTest extends TestCase
{
    public function test_OrderState(): void
    {
        $orderContext = new OrderContext();

        // 現在のステータス
        $status = $orderContext->getState();
        // A1 (実行対象)
        echo "\n現在のステータスは、" . $status . "\n";

        // => A2 (実行成功)
        $orderContext->toA2State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => A3 (実行失敗)
        $orderContext->toA3State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => B1 (1度実行失敗-もう一回対象)
        $orderContext->toB1State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => B2 (1度実行失敗-もう一回に成功)
        $orderContext->toB2State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => B3 (1度実行失敗-もう一回に失敗)
        $orderContext->toB3State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => C1 (実行失敗-ロールバック対象)
        $orderContext->toC1State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => C2 (実行失敗-ロールバック成功)
        $orderContext->toC2State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        // => C3 (実行失敗-ロールバック失敗)
        $orderContext->toC3State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは、" . $status . "\n";

        $this->assertTrue(true);
    }
}
