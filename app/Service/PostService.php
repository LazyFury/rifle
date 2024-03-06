<?php

namespace App\Service;

use App\Models\Post;
use Common\Service\Service;

class PostService extends Service
{

    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function export_column_names()
    {
        $names = parent::export_column_names();
        $names[] = "作者";
        return $names;
    }

    public function export_serialize()
    {
        $arr = parent::export_serialize();
        $arr["author"] = $this->model->author ? $this->model->author['name'] : null;
        return $arr;
    }

}