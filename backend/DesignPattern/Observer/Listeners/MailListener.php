<?php

namespace DesignPattern\Observer\Listeners;

use DesignPattern\Observer\ObserverInterface;
use DesignPattern\Observer\Subjects\BulletinBoard;

class MailListener implements ObserverInterface
{
    public function execute(BulletinBoard $board)
    {
        // メール送信処理
        echo '<small>メールの送信を行いました</small><br>';
    }
}
