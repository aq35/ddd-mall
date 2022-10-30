<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\OrderContext;
use DDD\Order\OrderState\A3Exception;
use DDD\Order\OrderState\B3Exception;
use DDD\Order\OrderState\C3Exception;

class OrderContextTest
{
    public static function test($id = '_')
    {
        $orderContext = new OrderContext();

        try {
            // A3 (実行失敗)
            if ($id == 'A') {
                throw new A3Exception('', 200, new \RuntimeException());
                // B3 (1度実行失敗-もう一回に失敗)
            } else if ($id == 'B') {
                throw new B3Exception('', 200, new \RuntimeException());
                // C3 (実行失敗-ロールバック失敗)
            } else if ($id == 'C') {
                throw new C3Exception('', 200, new \RuntimeException());
            }
        } catch (A3Exception $e) {
            // $eは塗りつぶさないこと
            $orderContext->toA3State();
            // $previous = $e->getPrevious();
            // if (is_null($previous)) {
            //     throw $e; // A3Exceptionそのもの
            // } else {
            //     throw $previous; // 上の例外
            // }
        } catch (B3Exception $e) {
            // $eは塗りつぶさないこと
            $orderContext->toB3State();
            // $previous = $e->getPrevious();
            // if (is_null($previous)) {
            //     throw $e; // A3Exceptionそのもの
            // } else {
            //     throw $previous; // 上の例外
            // }
        } catch (C3Exception $e) {
            // $eは塗りつぶさないこと
            $orderContext->toC3State();
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
