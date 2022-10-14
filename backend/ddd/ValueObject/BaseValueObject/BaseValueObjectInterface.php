<?php

namespace DDD\ValueObject\BaseValueObject;

interface BaseValueObjectInterface
{
    // 値の等価性
    // 等価性を判断するには、オブジェクトの型と、それぞれの型を比較する。
    public function equals(BaseValueObject $valueObject): bool;
}
