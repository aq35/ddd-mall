<?php

namespace DDD\Order\OrderState;

use DDD\Order\OrderState\OrderContext;

use DDD\Order\OrderState\A3Exception;
use DDD\Order\OrderState\B3Exception;
use DDD\Order\OrderState\C3Exception;

use DDD\Order\OrderState\State\A1State;
use DDD\Order\OrderState\State\B1State;
use DDD\Order\OrderState\State\C1State;

class OrderContextTest
{
    public static function test($id = '_')
    {
        $orderContext = OrderContext::a1();
        if ($id == 'SUCCESS_A') {
            $orderContext = OrderContext::a1();
        } else if ($id == 'SUCCESS_B') {
            $orderContext = OrderContext::b1();
        } else if ($id == 'SUCCESS_C') {
            $orderContext = OrderContext::c1();
        }

        try {
            // A3 (実行失敗)
            if ($id == 'A3') {
                throw new A3Exception('', 200, new \RuntimeException());
                // B3 (1度実行失敗-もう一回に失敗)
            } else if ($id == 'B3') {
                throw new B3Exception('', 200, new \RuntimeException());
                // C3 (実行失敗-ロールバック失敗)
            } else if ($id == 'C3') {
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

        $orderContext->toSuccessState();

        return $orderContext;
    }
}
