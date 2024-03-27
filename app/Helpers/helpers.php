<?php

use App\Models\DictGroup;

/**
 * Get the hot posts.
 */
if (! function_exists('get_hot_posts')) {
    function get_hot_posts($limit = 5)
    {
        return \App\Models\Post::query()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

/**
 * Get post categories.
 */
if (! function_exists('get_post_categories')) {
    function get_post_categories($parent_id = null)
    {
        return \App\Models\PostCategory::where('parent_id', $parent_id)->get();
    }
}

/**
 * Get navigation items.
 */
if (! function_exists('get_nav_items')) {
    function get_nav_items()
    {
        return [
            [
                'title' => 'Home',
                'url' => '/',
            ],
            [
                'title' => 'Store',
                'url' => '/store',
            ],
            [
                'title' => 'Blog',
                'url' => '/posts',
            ],
            [
                'title' => 'About',
                'url' => '/about',
            ],
            [
                'title' => 'Contact',
                'url' => '/contact',
            ],
        ];
    }
}

/**
 * is actived url
 */
if (! function_exists('is_active_url')) {
    function is_active_url($url)
    {
        $url = str_replace('/', '', $url);

        if ($url == '') {
            return request()->is('/');
        }

        return request()->is($url);
    }
}

/**
 * Get Config in DictGroup
 */
if (! function_exists('get_config')) {
    function get_config($key, $default = null)
    {
        $arr = DictGroup::where('key', $key)->first();
        if (! $arr) {
            return $default;
        }
        $config = [];
        foreach ($arr->dicts()->get() as $dict) {
            $config[$dict->key] = $dict->value;
        }

        return $config;
    }
}

/**
 * calc page loading time
 */
if (! function_exists('calc_page_loading_time')) {
    function calc_page_loading_time()
    {
        $unix = microtime(true) - LARAVEL_START;
        $time = round($unix, 3);

        return "Page loaded in {$time} seconds.";
    }
}
