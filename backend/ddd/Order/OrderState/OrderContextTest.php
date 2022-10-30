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
            throw new A3Exception('', 200, new \RuntimeException());
        } catch (A3Exception $e) {
            // $eは塗りつぶさないこと
            $orderContext->toA3State();
            // $previous = $e->getPrevious();
            // if (is_null($previous)) {
            //     throw $e; // A3Exceptionそのもの
            // } else {
            //     throw $previous; // 上の例外
            // }
        }

        return $orderContext;
    }
}
