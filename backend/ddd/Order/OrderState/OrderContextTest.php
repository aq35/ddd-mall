<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\OrderContext;
use DDD\Order\OrderState\A3Exception;

class OrderContextTest
{
    public static function test()
    {
        $orderContext = new OrderContext();

        try {
            // 決済の処理
            throw new A3Exception();
        } catch (A3Exception $e) {
            // $eは塗りつぶさないこと
            $orderContext->toA3State();
        }

        return $orderContext;
    }
}
