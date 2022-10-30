<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DDD\Order\OrderState\OrderContext;
use DDD\Order\OrderState\OrderContextTest;

// ステータス管理:1つのステータス管理
// ./vendor/bin/phpunit DesignPattern/Tests/OrderStateMiddlewareTest.php
final class OrderStateMiddlewareTest extends TestCase
{
    public function test_OrderStateMiddleware(): void
    {
        $test  = new OrderContextTest();
        echo "\n";
        $data = $test->test('A');
        echo $data->getState() . "\n";

        $data = $test->test('B');
        echo $data->getState() . "\n";

        $data = $test->test('C');
        echo $data->getState() . "\n";

        $this->assertTrue(true);
    }
}
