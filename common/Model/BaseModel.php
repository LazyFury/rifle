<?php

namespace Common\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UseTimeFormatTairt,UsePersonalFakeTrait;
}
