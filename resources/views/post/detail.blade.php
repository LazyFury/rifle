@extends('layout/layout')

@section('title', 'Post Detail')


@section('content')
    <div class="flex flex-row gap-2">
        <div class="my-4 gap-2 flex-1">
            <div class="flex-1">
                <div>
                    <h1>{{ $post['title'] }}</h1>
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
