<?php

namespace App\Models;

use Common\Model\BaseModel;

class UserModel extends BaseModel{
    // tablename
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getEmailAttribute($value){
        // hidden some
        return substr($value,0,3).'****'.substr($value,-3);
    }
}
