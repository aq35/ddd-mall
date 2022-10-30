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
        echo "\n現在のステータスは" . $status . "\n";

        // ステータスを進める
        $orderContext->toA2State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは" . $status . "\n";

        // ステータスを戻す
        $orderContext->backA1State();
        $status = $orderContext->getState();
        echo "\n現在のステータスは" . $status . "\n";

        $this->assertTrue(true);
    }
}
