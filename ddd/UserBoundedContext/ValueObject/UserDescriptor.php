<?php

namespace DDD\UserBoundedContext\ValueObject;

use DDD\UserBoundedContext\ValueObject\TenantId;

/*
サービスのメソッドからUserDescriptorを返す。

これは、ユーザーごとのWebセッションを格納するのに適している。

クライアントのアプリケーションサービスは、サービスの呼び出し元にこのオブジェクトをそのまま返したり、あるいは、その用のオブジェクトをもう一つ作ってそれを返したりする。

User全体を返すのではなく、Userを参照するために欠かせない属性だけをまとめました。

*/
final class UserDescriptor
{
    private string $email;
    private TenantId $tenantId;
    private string $username;

    public function __construct(
        string $email,
        TenantId $tenantId,
        string $username
    )
    {
        $this->email = $email;
        $this->tenantId = $tenantId;
        $this->username = $username;
    }
}
    