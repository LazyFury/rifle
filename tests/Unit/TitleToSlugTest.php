<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TitleToSlugTest extends TestCase
{
    public function test_title_to_slug(): void
    {
        $title = 'Hello World';
        $slug = title_to_slug($title);
        $this->assertEquals('hello-world', $slug);

        $title = '史上最全的 Laravel 教程」「」 - Laravel 学院！！！@#￥%……&*（）——+';
        $slug = title_to_slug($title);
        $this->assertEquals('史上最全的-laravel-教程---laravel-学院', $slug);
    }
}
