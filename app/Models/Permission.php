<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = "permissions";
}
