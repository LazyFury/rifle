<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = "roles";
}
