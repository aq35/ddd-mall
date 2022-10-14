<?php

namespace DDD\ValueObject\BaseValueObject;

use Ulid\Ulid;

abstract class BaseValueObject implements BaseValueObjectInterface
{
    // 一意なIDを作成する
    protected static function generateId(): string
    {
        return Ulid::generate();
    }

    // 値の等価性
    // 等価性を判断するには、オブジェクトの型と、それぞれの型を比較する。
    public function equals(BaseValueObject $valueObject): bool
    {
        $equals = true;
        foreach ($valueObject as $key => $value) {
            if (!isset($this->{$key}) || (isset($this->{$key}) && $this->{$key} != $value)) {
                $equals = false;
                break;
            }
        }
        return $equals;
    }
}
