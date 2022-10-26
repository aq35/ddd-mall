<?php

namespace DDD\Tests;

use PHPUnit\Framework\TestCase;

use DesignPattern\Event\ForClient\AsyncEventEmitter;
use DesignPattern\Async\Co;

// ./vendor/bin/phpunit DesignPattern/Tests/OrderAsyncTest.php
final class OrderAsyncTest extends TestCase
{
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
