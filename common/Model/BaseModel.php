<?php

namespace Common\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UseTimeFormatTairt, UsePersonalFakeTrait, UseSearchable, UseDisableDelete;

    public function rules()
    {
        return $this->rules;
    }

    public function messages()
    {
        return $this->messages;
    }

}
