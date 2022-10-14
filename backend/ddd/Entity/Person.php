<?php

namespace DDD\Entity;

use DDD\Entity\BaseEntity\BaseEntity;

final class Person extends BaseEntity
{
    /**
     * @var string
     */
    private string $tenantId;

    public function setTenantId(string $aTenantId)
    {
        $this->tenantId = $aTenantId;
    }
}
