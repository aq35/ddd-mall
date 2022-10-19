<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\AsyncEventEmitter;
use DesignPattern\Async\Co;

// ./vendor/bin/phpunit DesignPattern/Tests/OrderAsyncTest.php
final class OrderAsyncTest extends TestCase
{
    // public function test_EventEmitter_async_emitters(): void
    // {
    //     $asyncEmitter = new AsyncEventEmitter();
    //     try {
    //         $this->eventDriven($asyncEmitter);
    //         $asyncEmitter->getQueue()->start();
    //         echo "aa" . "\n";
    //     } catch (\Exception $e) {
    //         echo "失敗" . "\n";
    //     }
    //     $asyncEmitter->getQueue()->stop();
    //     echo "テスト" . "\n";
    //     $this->assertTrue(true);
    // }

    // public function eventDriven(AsyncEventEmitter $asyncEmitter): void
    // {
    //     $i = 0;
    //     echo "\n";
    //     echo "非同期処理";
    //     echo "\n";

    //     $i += 1;
    //     $asyncEmitter->getQueue()->onStart(function () use ($asyncEmitter, $i) {
    //         echo "\e[31m [$i 番目] onStart \e[m" . "\n";
    //     });
    //     // -------------------------------------------------
    //     echo "\n";
    //     echo "非同期処理";
    //     echo "\n";
    //     $i += 1;
    //     $asyncEmitter->on('event', function () use ($i) {
    //         echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
    //     });
    //     echo "\n";
    //     echo "非同期処理";
    //     echo "\n";
    //     $i += 1;
    //     $asyncEmitter->on('event', function () use ($i, $asyncEmitter) {
    //         echo "\e[31m [$i 番目] 'event' AsyncEventEmitter \e[m" . "\n";
    //     });
    //     echo "\n";
    //     echo "非同期処理";
    //     echo "\n";
    //     $asyncEmitter->emit('event');

    //     $i += 1;
    //     $asyncEmitter->getQueue()->onAfterTick(function () use ($asyncEmitter, $i) {
    //         echo "\e[31m [$i 番目] onAfterTick \e[m" . "\n";
    //         $asyncEmitter->getQueue()->stop();
    //     });
    //     echo "\n";
    //     echo "非同期処理";
    //     echo "\n";
    //     $i += 1;
    //     $asyncEmitter->getQueue()->onStop(function () use ($asyncEmitter, $i) {
    //         echo "\e[31m [$i 番目] onStop \e[m" . "\n";
    //     });
    // }

    public function test_EventEmitter_async_emitters(): void
    {
        $co = Co::wait([
            'Delay 5 secs' => function () {
                echo "[Delay] I start to have a pseudo-sleep in this coroutine for about 5 secs\n";
                for ($i = 0; $i < 5; ++$i) {
                    yield Co::DELAY => 1;
                    if ($i < 4) {
                        printf("[Delay] %s\n", str_repeat('.', $i + 1));
                    }
                }
                echo "[Delay] Done!\n";
            },
            "google.com HTML" => $this->curl_init_with("https://google.com"),
            "Content-Length of github.com" => function () {
                echo "[GitHub] I start to request for github.com to calculate Content-Length\n";
                $content = yield $this->curl_init_with("https://github.com");
                echo "[GitHub] Done! Now I calculate length of contents\n";
                return strlen($content);
            },
            "Save mpyw's Gravatar Image URL to local" => function () {
                echo "[Gravatar] I start to request for github.com to get Gravatar URL\n";
                $src = (yield $this->get_xpath_async('https://github.com/mpyw'))
                    ->evaluate('string(//img[contains(@class,"avatar")]/@src)');
                echo "[Gravatar] Done! Now I download its data\n";
                yield $this->curl_init_with($src, [CURLOPT_FILE => fopen('/tmp/mpyw.png', 'wb')]);
                echo "[Gravatar] Done! Saved as /tmp/mpyw.png\n";
                $this->assertTrue(true);
            }
        ]);
        var_dump($co);
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
