<?php

namespace DDD\Entity;

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
