<?php

namespace DesignPattern\Observer\Subjects;

use DesignPattern\Observer\ObserverInterface;
use DesignPattern\Observer\SubjectInterface;

class BulletinBoard implements SubjectInterface
{
    private $name;
    private $comments = [];
    private $listeners = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function comment($message)
    {
        echo sprintf('%s : %s<br>', $this->getName(), $message);
        $this->addComment($message);
    }

    public function addComment($message)
    {
        $this->comments[] = $message;
        $this->notify();
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addObserver(ObserverInterface $listener)
    {
        $this->listeners[get_class($listener)] = $listener;
    }
    public function removeObserver(ObserverInterface $listener)
    {
        unset($this->listeners[get_class($listener)]);
    }
    public function notify()
    {
        foreach ($this->listeners as $listener) {
            $listener->execute($this);
        }
    }
}
