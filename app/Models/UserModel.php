<?php

namespace App\Models;

use Common\Model\BaseModel;


/**
 * User alias
 */
class UserModel extends BaseModel
{
    // tablename
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
        'id'
    ];

    public function getEmailAttribute($value)
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
