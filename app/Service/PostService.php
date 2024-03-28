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
        $names[] = '作者';

        return $names;
    }

    public function export_serialize()
    {
        $arr = parent::export_serialize();
        $arr['author'] = $this->model->author ? $this->model->author['name'] : null;

        return $arr;
    }

    public function save_post_before($request)
    {
        $slug = $request->slug ?? $request->title;
        $slug = title_to_slug($slug);
        // max-count 32
        $slug = substr($slug, 0, 32);

        $find = $this->model->query()
            ->where('slug', $slug)
            ->where('id', '!=', $request->id)
            ->first();

        if ($find) {
            $slug = $slug.'-'.time();
        }

        $request->merge(['slug' => $slug]);
    }
}
