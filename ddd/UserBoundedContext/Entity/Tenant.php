<?php

namespace DDD\UserBoundedContext\Entity;

final class Tenant
{
    /**
     * @var string
     */
    private string $name;

    // アクティベート
    public function activate()
    {
    }

    // デアクティベート
    public function deactivate()
    {
    }
}
