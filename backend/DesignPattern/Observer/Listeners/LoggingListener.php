<?php

namespace DesignPattern\Observer\Listeners;

use DesignPattern\Observer\ObserverInterface;
use DesignPattern\Observer\Subjects\BulletinBoard;

class LoggingListener implements ObserverInterface
{
    public function execute(BulletinBoard $board)
    {
        // ログ書き込み処理
        echo '<small>ログ書き込みを行いました</small><br>';
    }
}
