<?php

namespace App\Models;

use Common\Model\BaseModel;
use Common\Model\UseTimeFormatTairt;


/**
 * User alias
 */
class UserModel extends BaseModel
{
    use UseTimeFormatTairt;
    // tablename
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $rules = [
        "name" => "required|string|max:20",
    ];

    protected $messages = [
        "name.required" => "用户名不能为空",
        "name.string" => "用户名必须是字符串",
        "name.max" => "用户名最大长度为20",
    ];

    protected $fillable = [
        "name",
    ];

    protected $casts = [
    ];

    protected $appends = [
        'hidden_email'
    ];

    public function getHiddenEmailAttribute($value)
    {
        // hidden some
        return substr($value, 0, 3) . '****' . substr($value, -3);
    }

    public function get_searchable()
    {
        return [
            'name',
            'email',
            "id"
        ];
    }
}
