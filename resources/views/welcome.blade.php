@extends('layout/layout')

@section('title', 'Welcome to my first Laravel application')

@section('content')

    <div class="grid grid-cols-8 my-4 gap-2">
        @foreach (get_hot_posts(8) as $post)
            <div class="relative">
                <img src="https://dummyimage.com/300x200"
                    alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                    class="w-full h-96px object-cover object-center block bg-gray-300" />
                <div class=" absolute left-0 bottom-0 w-full bg-#33333380 p-1 box-border" style="">
                    <a href="/post/{{ $post['slug'] }}" class="inline-block text-sm text-white" title="{{ $post['title'] }}">
                        <span>{{ text_cut($post['short_title'],10) }}</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="main-container flex flex-row flex-wrap gap-2 py-2">



        <div class="flex-1">
            <div>
                <!-- swiper container w-full h-350 gray  -->
                <div class="swiper w-full h-250px bg-gray-300">
                    <!-- swiper wrapper  -->
                    <div class="swiper-wrapper !w-full">
                        <!-- swiper slide  -->
                        <div class="swiper-slide !w-full">
                            <img src="https://dummyimage.com/900x300"
                                alt="slide 1" class="w-full h-full object-cover object-center">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 my-4 gap-2">
                @foreach (get_hot_posts(8) as $post)
                    <div class="bg-white">
                        <img src="https://dummyimage.com/300x200"
                            alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                            class="w-full object-cover object-center block bg-gray-300" />
                        <div class="p-2 pt-0">
                            <a href="/post/{{ $post['slug'] }}" class="mt-2 inline-block text-gray-600" title="{{ $post['title'] }}">
                                <span>{{ text_cut($post['title'],24) }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="mt-2 font-bold bg-gray-200 p-2 gap-4 px-4 flex flex-row">
                @foreach (get_post_categories() as $category)
                    <a href="/posts?category_id={{ $category['id'] }}">{{ $category['name'] }}</a>
                @endforeach
            </div>


            @foreach (get_hot_posts(3) as $post)
                <div class="bg-white my-2 flex flex-row flex-wrap gap-2">
                    <img src="https://dummyimage.com/300x200"
                        alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                        class="w-200px object-cover object-center block bg-gray-300">
                    <div class="py-2 flex-1">
                        <a href="/post/{{ $post['slug'] }}"  title="{{ $post['title'] }}">
                            <h3 class="my-0 mb-1 font-normal">{{ $post['short_title'] }}</h3>
                        </a>

                        <p class="my-0 text-gray-500">{{ $post['short_content'] }}</p>
                        <!-- tags  -->
                        <div class="mt-2 flex flex-row flex-wrap gap-2">
                            @foreach ($post['tags'] as $tag)
                               <div class="bg-gray-200 rounded px-2 py-0.5">
                                <a href="/posts?tag_id={{ $tag['id'] }}">
                                        <span >{{ $tag['name'] }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-2">
                            <span class="text-gray">发布于:{{ $post['created_at'] }}</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="bg-white w-320px p-4">
            Hello Laravel!




            <!-- posts with pic  -->
            <h3 class="mb-2 text-primary slide-title">Posts with Pic</h3>
            <div class="grid grid-cols-3 gap-1">
                @foreach (get_hot_posts(3) as $post)
                    <div>
                        <!-- def pic  -->
                        <img src="https://dummyimage.com/300x200"
                            alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                            class="w-full h-64px object-cover object-center block bg-gray-300" />
                        <a href="/post/{{ $post['slug'] }}" class="mt-2 inline-block text-sm" title="{{ $post['title'] }}">
                            <span>{{ text_cut($post['short_title'],6) }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

            <h3 class="mb-2 text-primary slide-title">Hot Posts</h3>
            @foreach (get_hot_posts(5) as $post)
                <div class="mb-2">
                    <a href="/post/{{ $post['slug'] }}">
                        <span class="my-0">{{ text_cut($post['title'],20) }}</span>
                    </a>
                </div>
            @endforeach

            <!-- all categoies  -->
            <h3 class="mb-2 text-primary slide-title">All Categories</h3>
            <div class="flex flex-col">
                @foreach (get_post_categories() as $category)
                    <div class="flex flex-row gap-2">
                        <div class="mb-2">
                            <a href="/posts?category_id={{ $category['id'] }}">
                                <span class="my-0">{{ $category['name'] }}</span>
                            </a>
                        </div>
                        @foreach ($category['children'] as $child)
                            <div class="mb-2 bg-gray-200 px-1 rounded-2px">
                                <a href="/posts?category_id={{ $child['id'] }}">
                                    <span class="my-0">{{ $child['name'] }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

    </div>



    <script>
        var swiper = new Swiper('.swiper', {
            // Optional parameters
            loop: true,
            autoplay: {
                delay: 3000,
            },
            // If we need pagination
        });
    </script>
@endsection
