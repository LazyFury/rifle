@extends('layout/layout')   


@section('title', 'Welcome to my first Laravel application')


@section('content')

Posts 

@foreach ($posts as $post)
    <div class="bg-white p-2 my-2">
        <a href="/post/{{ $post->slug }}" class="text-lg font-bold">
        <!-- {{ $post->title }} -->
        @if($q)
        @foreach(split_text($post->title,$q) as $title)
            <span class="{{
                $title['is_key'] ? 'text-red-500' : ''
            }}">{{ $title['text'] }}</span>
        @endforeach
        @else
        {{ $post->title }}
        @endif
        </a>
        <p>
        <!-- {{ $post->content }} -->
        @if($q)
        @foreach(split_text($post->short_content,$q) as $content)
            <span class="{{
                $content['is_key'] ? 'text-red-500' : ''
            }}">{{ $content['text'] }}</span>
        @endforeach
        @else
        {{ $post->short_content }}
        @endif
        </p>
        <div class="tags">
            @foreach ($post->tags as $tag)
                <a href="/posts?tag_id={{ $tag->id }}" class="{{
                    $tag->id == $tag_id ? 'text-red-500' : ''
                }}">{{ $tag->name }}</a>
            @endforeach
        </div>
        <div>
            <span class="text-sm">{{ $post->created_at }}</span>
            @if($post->category_id)
            <span class="text-sm">{{ $post->category->name }}</span>
            @endif
        </div>
    </div>
@endforeach

{{ $pagination->links() }}

@endsection