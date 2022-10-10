<?php

namespace DesignPattern\Observer;

use DesignPattern\Observer\ObserverInterface;

interface SubjectInterface
{
    // Observerを登録します。
    public function addObserver(ObserverInterface $listener);

    // Observerを削除します。
    public function removeObserver(ObserverInterface $listener);

    // 状態変化をObserverへ通知します。
    public function notify();
}
