<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;
use DesignPattern\Async2\Internal\GeneratorContainer;
use Exception;

// ./vendor/bin/phpunit DesignPattern/Tests/Async2Test.php
final class Async2Test extends TestCase
{
    public function test_同期的(): void
    {
        // 0,何もしない
        // 1,即実行対象
        // 2,実行成功
        // 3,実行失敗-ロールバック不要
        // 4,実行失敗-ロールバック対象
        // 5,実行失敗-ロールバック成功
        // 6,実行失敗-ロールバック失敗
        $status = ['1' => 0, '2' => 0, '3' => 0];
        $status['1'] = 1;
        echo "処理を開始します\n";
        $generatorContainer1 =  new GeneratorContainer($this->generatorForTest1());
        $generatorContainer2 = null;
        $generatorContainer3 = null;

        // 直列1　
        // isWorkingGenerator が trueの間は、実行する
        while ($generatorContainer1->isWorkingGenerator()) {
            $generatorContainer1->send('abcde');
        }
        $exception1 = $generatorContainer1->currentOrFail();
        if ($exception1 instanceof Exception) {
            $status['1'] = 3;
            echo "1 失敗\n";
            $this->assertTrue(true);
        } else {
            // イベント発火
            $status['1'] = 2;
            $status['2'] = 1;
            $status['3'] = 1;
            echo "1 成功\n";
            echo "2 イベント発火\n";
            echo "3 イベント発火\n";
            $this->assertTrue(true);
        }

        if ($status['2'] == 1) {
            $generatorContainer2 = new GeneratorContainer($this->generatorForTest2());
        }
        if ($status['3'] == 1) {
            $generatorContainer3 = new GeneratorContainer($this->generatorForTest3());
        }

        // イベントがあれば実行する
        if ($generatorContainer2) {
            while ($generatorContainer2 && $generatorContainer2->isWorkingGenerator()) {
                $generatorContainer2->send('abcde');
            }

            $exception2 = $generatorContainer2->currentOrFail();
            if ($exception2 instanceof Exception) {
                $status['2'] = 3; // ロールバック不要
                echo "2 イベント 失敗\n";
            } else {
                $status['2'] = 2;
                echo "2 イベント 成功\n";
                $this->assertTrue(true);
            }
        }

        // イベントがあれば実行する
        if ($generatorContainer3) {
            while ($generatorContainer3 && $generatorContainer3->isWorkingGenerator()) {
                $generatorContainer3->send('abcde');
            }

            $exception3 = $generatorContainer3->currentOrFail();
            if ($exception3 instanceof Exception) {
                $status['3'] = 3; // ロールバック不要
                echo "3 イベント 失敗\n";
                $status['2'] = 4; // 4,ロールバック対象
            } else {
                $status['3'] = 2;
                echo "3 イベント 成功\n";
                $this->assertTrue(true);
            }
        }

        // ロールバックをする
        if ($status['2'] == 4) {
            // ロールバックに成功
            $status['2'] = 5;
            if ($status['2'] == 5) {
                $status['1'] = 4; // ロールバック対象
                if ($status['1'] == 4) {
                    if ($status['2'] == 5) {
                        $status['1'] = 4; // ロールバック対象
                        if ($status['1'] == 4) {
                            $status['1'] = 5; // ロールバック成功
                        }
                    }
                }
            }
        }

        echo json_encode($status);
        // 直列2 並列2

        //$aa = $generatorContainer1->currentOrFail();
        //dd($aa);
        $this->assertTrue(true);
    }

    function generatorForTest1()
    {
        $eventName = yield; // sendのパラメータ
        echo $eventName;
        echo "[Delay] I start to have a pseudo-sleep in this coroutine for about 5 secs\n";
        for ($i = 0; $i < 5; ++$i) {
            yield '__DELAY__' => 1;
            if ($i < 4) {
                printf("[Delay] %s\n", str_repeat('.', $i + 1));
            }
        }
        // throw new Exception('途中で失敗しました');
        echo "[Delay] Done!\n";
    }

    function generatorForTest2()
    {
        echo "[GitHub] I start to request for github.com to calculate Content-Length\n";
        $content = yield $this->curl_init_with("https://github.com");
        echo "[GitHub] Done! Now I calculate length of contents\n";
        return strlen($content);
        // throw new Exception('途中で失敗しました');
    }

    function generatorForTest3()
    {
        echo "[Google]\n";
        $content = yield $this->curl_init_with("https://google.com");
        echo "[Google]\n";
        throw new Exception('途中で失敗しました');
        return strlen($content);
    }

    function curl_init_with($url, array $options = [])
    {
        $options = array_replace([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ], $options);
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        return $response;
    }

    function get_xpath_async(string $url): \Generator
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML(yield $this->curl_init_with($url));
        return new \DOMXPath($dom);
    }
}
