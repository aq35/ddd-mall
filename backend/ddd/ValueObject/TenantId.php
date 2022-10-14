<?php

namespace DDD\ValueObject;

use DDD\ValueObject\BaseValueObject\BaseValueObject;

final class TenantId extends BaseValueObject
{
    private string $tenantId;

    public function __construct(
        string $tenantId
    ) {
        $this->tenantId = $tenantId;
    }

    public static function generate()
    {
        return new TenantId(parent::generateId());
    }

    public function getUserId()
    {
        return $this->tenantId;
    }
}
