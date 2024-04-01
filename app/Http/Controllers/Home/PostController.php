<?php

namespace App\Http\Controllers\Home;

use App\Models\Post;
use App\Models\PostCategory;
use App\Service\PostService;
use Illuminate\Http\Request;

class PostController
{
    protected $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function detail($slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->first();

        return view('post/detail', [
            'post' => $post,
        ]);
    }

    public function list(Request $request)
    {
        $query = Post::query();
        // category_id
        $category_id = $request->input('category_id', null);
        if ($category_id) {
            $category = PostCategory::query()
                ->where('id', $category_id)
                ->first();
            $children_categories = $category->get_all_children_ids();
            $children_categories[] = $category_id;
            $query->whereIn('category_id', $children_categories);
        }

        // tag_id
        $tag_id = $request->input('tag_id', null);
        if ($tag_id) {
            $query->where('tags_ids', 'like', "%{$tag_id}%");
        }

        // q search,like title,content
        $q = $request->input('q', null);
        if ($q) {
            $query->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        }

        $posts = $query->orderBy('id', 'desc')->orderBy('updated_at', 'desc');
        $pagination = $posts->paginate(10);

        return view('post/list', [
            'posts' => $pagination->items(),
            'pagination' => $pagination,
            'category_id' => $category_id,
            'tag_id' => $tag_id,
            'q' => $q,
        ]);
    }
}
