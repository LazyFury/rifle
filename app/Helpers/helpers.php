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

        if ($url == 'posts') {
            // also /post/{id,slug}
            return request()->is('post*');
        }

        return request()->is($url);
    }
}

/**
 * title to slug
 */
if (! function_exists('title_to_slug')) {
    function title_to_slug($title)
    {
        $not_allow_chars = ['!', '！', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '[', ']', '{', '}', '|', '\\', ';', ':', '"', '\'', ',', '<', '>', '.', '?', '/'];
        $not_allow_chinese_chars = ['，', '。', '？', '！', '“', '”', '‘', '’', '；', '：', '、', '（', '）', '【', '】', '《', '》', '——', '……', '￥', '…', '·', '「', '」', '『', '』', '〈', '〉', '〖', '〗', '【', '】', '﹏', '＿', '＼', '／', '＆', '％', '＃', '＠', '＊', '＋', '－', '＝', '＜', '＞', '～', '｜', '｛', '｝', '｟', '｠', '｢', '｣', '､', '、', '〃', '》', '「', '」', '『', '』', '【', '】', '〔', '〕', '〖', '〗', '〘', '〙', '〚', '〛', '〜', '〝', '〞', '〟', '〰', '〾', '〿', '–', '—', '‘', '’', '‛', '“', '”', '„', '‟', '†', '‡', '•', '‣', '․', '‥', '…', '‧', '‰', '‱', '′', '″', '‴', '‵', '‶', '‷', '‸', '‹', '›', '※', '‼', '‽', '‾', '‿', '⁀', '⁁', '⁂', '⁃', '⁄', '⁇', '⁈', '⁉', '⁊', '⁋', '⁌', '⁍', '⁎', '⁏', '⁐', '⁑', '⁒', '⁓', '⁔', '⁕', '⁖', '⁗', '⁘', '⁙', '⁚', '⁛', '⁜'];
        $slug = str_replace($not_allow_chars, '', $title);
        $slug = str_replace($not_allow_chinese_chars, '', $slug);
        $slug = str_replace(' ', '-', $slug);

        // chinese to pinyin

        // lower
        $slug = strtolower($slug);

        return $slug;
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
        $start = defined('LARAVEL_START') ? LARAVEL_START : $_SERVER['REQUEST_TIME_FLOAT'];
        $unix = microtime(true) - $start;
        $time = round($unix, 3);

        return "Page loaded in {$time} seconds.";
    }
}

/**
 * text cut as short
 */
if (! function_exists('text_cut')) {
    function text_cut($text, $length = 32)
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length).(mb_strlen($text) > $length ? '...' : '');
    }
}

/**
 * split title in search as array
 */
if (! function_exists('split_text')) {
    function split_text($title, $k)
    {
        $arr = explode($k, $title);
        if (count($arr) == 1) {
            return [
                [
                    'text' => $arr[0],
                    'is_key' => false,
                ],
            ];
        }

        if (count($arr) == 2) {
            return [
                [
                    'text' => $arr[0],
                    'is_key' => false,
                ],
                [
                    'text' => $k,
                    'is_key' => true,
                ],
                [
                    'text' => $arr[1],
                    'is_key' => false,
                ],
            ];
        }

    }
}

/**
 * all tags
 */
if (! function_exists('get_all_tags')) {
    function get_all_tags()
    {
        return \App\Models\PostTag::all();
    }
}
