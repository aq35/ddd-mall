<?php

namespace DDD\Entity;

use DDD\Entity\User;
use DDD\Entity\Person;

final class Tenant extends BaseEntity
{
    /**
     * @var string
     */
    private string $tenantId;

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

    public function registerUser(string $aUserName, string $aEmail, string $aPassword, Person $aPerson): User
    {
        $aPerson->setTenantId($this->tenantId);
        $user = User::register($aEmail, $aPassword, $aPerson);
        return $user;
    }
}
