<?php

namespace App\Http\Controllers\Home;

use App\Models\Post;

class PostController
{
    public function detail($slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->first();

        return view('post/detail', [
            'post' => $post,
        ]);
    }
}
