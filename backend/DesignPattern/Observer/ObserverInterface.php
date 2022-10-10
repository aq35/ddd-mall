<?php

namespace DesignPattern\Observer;

use DesignPattern\Observer\Subjects\BulletinBoard;

interface ObserverInterface
{
    public function execute(BulletinBoard $board);
}
