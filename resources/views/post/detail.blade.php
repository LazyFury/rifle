@extends('layout/layout')

@section('title', text_cut($post['title'], 20))

@section('head')

    <!-- description -->
    <meta name="description" content="{{ $post['title'] }}">
    <!-- keywords -->
    <meta name="keywords" content="@foreach ($post['tags'] as $tag){{ $tag['name'] }}, @endforeach">
@endsection


@section('content')
    <div class="flex flex-row gap-2 items-start">
        <div class="my-4 gap-2 flex-1">
            <div class="flex-1">
                <div>
                    <h1>{{ $post['title'] }}</h1>
                    <div>
                        <span>{{ $post['created_at'] }}</span>
                        @if ($post['category_id'])
                            <span>{{ $post['category']['name'] }}</span>
                        @endif
                    </div>
                    <p>{{ $post['content'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white w-320px p-4">
            Hello Laravel!

            @include('components.hot_posts')

            @include('components.hot_posts_style2')

            @include('components.all_categories')
        </div>

    </div>
@endsection
