<?php

namespace Common\Model;
use Illuminate\Auth\AuthenticationException;


/**
 * Trait UsePersonalTrait
 * 继承自 BaseModel 的模型，有可能实现了 UsePersonalTrait，这个 trait fake定义了 scopeUsePersonal 方法
 * 处理方法不存在的情况
 */
trait UsePersonalFakeTrait {
    // $user_id_column 属性
    protected string $user_id_column;

    // 是否必须登录才能使用
    protected bool $must_auth = true;

    public function scopeUsePersonal($query, $user_id = null, $must_auth = true)
    {
        return $query;
    }

    public function getUserIdColumn()
    {
        return $this->user_id_column;
    }

    // handle user is null
    public function handleUserIsNull($query)
    {
        if ($this->must_auth) {
            throw new AuthenticationException('UsePersonalTrait: Unauthenticated. User is null.');
        }
        return $query;
    }
}
