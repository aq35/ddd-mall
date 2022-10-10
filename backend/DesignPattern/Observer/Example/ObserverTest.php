<?php

namespace DesignPattern\Observer\Example;

use DesignPattern\Observer\Subjects\BulletinBoard;
use DesignPattern\Observer\Listeners\LoggingListener;
use DesignPattern\Observer\Listeners\MailListener;
use DesignPattern\Observer\Listeners\SlackListener;

class ObserverTest
{
    public static function test()
    {
        // 掲示板クラス　インスタンス生成
        $user_1 = new BulletinBoard('rito');

        // リスナー登録
        $user_1->addObserver(new LoggingListener());
        $user_1->addObserver(new MailListener());
        $user_1->addObserver(new SlackListener());

        $user_1->comment('おはよう');
        echo '<hr>';

        $user_1->comment('こんにちは');
        echo '<hr>';

        $user_1->comment('こんばんは');
        echo '<hr>';
    }
}
