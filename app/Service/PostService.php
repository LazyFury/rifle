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

}