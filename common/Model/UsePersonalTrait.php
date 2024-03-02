<?php

namespace Common\Model;
use Illuminate\Auth\AuthenticationException;

trait UsePersonalTrait {
    // $user_id_column 属性
    protected string $user_id_column;

    // 是否必须登录才能使用
    protected bool $must_auth = true;

    public function scopeUsePersonal($query, $user_id = null, $must_auth = true)
    {
        if ($user_id == null) {
            $user_id = auth()->id();
        }

        if ($user_id == null) {
            if ($must_auth) {
                throw new AuthenticationException('UsePersonalTrait:scopeUsePersonal must auth'); //  $this->must_auth 定义的公开数据可以查看，但是禁止修改删除
            }
            return $this->handleUserIsNull($query);
        }

        return $query->where([
            $this->getUserIdColumn() => $user_id,
        ]);
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
