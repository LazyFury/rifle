<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Dict extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'description',
        'group_id',
        "key",
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        // 约束 dicts_key_group_id_unique
        'key' => [
            'required',
            'string',
            'max:255',
        ],
        'value' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'group_id' => 'nullable|integer|exists:dict_groups,id',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'name.string' => 'Name must be a string',
        'name.max' => 'Name must not be greater than 255 characters',
        'value.required' => 'Value is required',
        'value.string' => 'Value must be a string',
        'value.max' => 'Value must not be greater than 255 characters',
        'description.string' => 'Description must be a string',
        'description.max' => 'Description must not be greater than 255 characters',
        'group_id.integer' => 'Group ID must be an integer',
        'group_id.exists' => 'Group ID must exist in the dict_groups table',
        "name.unique" => "字典名称已存在",
        "key.unique" => "字典键已存在",
        "key.required" => "字典键不能为空",
        "key.string" => "字典键必须是字符串",
    ];

    protected $appends = [
        'group_name',
    ];

    /**
     * 获取验证规则
     * @param bool $isUpdate 是否是更新操作
     * @param array $data 数据
     * @return array
     * 
     * 如果 group_id 未改变，则查询 group_id,and key,and id != 当前记录id 的记录是否存在
     * 如果 group_id 改变，则查询 group_id,and key 的记录是否存在
     */
    public function rules(bool $isUpdate = false, array $data = [])
    {
        // 如果是更新操作，且 group_id 未改变，则忽略当前记录
        $group_id_changed = array_key_exists('group_id', $data) && $data['group_id'] != $this->group_id;

        // 如果是创建操作，不忽略id
        if (!$isUpdate) {
            $group_id_changed = true;
        }

        // 如果是创建，group_id 为data中的group_id，否则为当前记录的group_id
        $group_id = $data['group_id'] ?? $this->group_id;

        // 约束 dicts_key_group_id_unique
        $unique_rule = Rule::unique('dicts', "key")->where(function ($query) use ($group_id) {
            return $query->where('group_id', $group_id);
        });

        // 如果 group_id 未改变，则忽略当前记录
        if (!$group_id_changed) {
            $unique_rule = $unique_rule->ignore($this->id);
        }


        $this->rules['key'] = [
            'required',
            'string',
            'max:255',
            $unique_rule,
        ];
        return $this->rules;
    }

    // group 
    public function group()
    {
        return $this->belongsTo(DictGroup::class);
    }

    // group name
    public function getGroupNameAttribute()
    {
        return $this->group->name;
    }
}
