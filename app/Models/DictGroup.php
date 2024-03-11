<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictGroup extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        "key"
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'key' => "required|string|max:255|unique:dict_groups,key",
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'name.string' => 'Name must be a string',
        'name.max' => 'Name must not be greater than 255 characters',
        'description.string' => 'Description must be a string',
        'description.max' => 'Description must not be greater than 255 characters',
        "key.unique" => "字典组键已存在",
        "key.required" => "字典组键不能为空",
    ];

    protected $appends = [
        'dict_count',
    ];

    // dicts
    public function dicts()
    {
        return Dict::where('group_id', $this->id);
    }

    // dict count
    public function getDictCountAttribute()
    {
        return $this->dicts()->count();
    }
}
