<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dict;
use Common\Controller\CURD;

class DictController extends CURD
{
    public function __construct(Dict $model)
    {
        parent::__construct($model);
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->with('group');
    }

    public static function tag()
    {
        return '字典管理/字典';
    }
}