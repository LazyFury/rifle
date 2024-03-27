@extends('layout/layout')

@section('title', 'Welcome to my first Laravel application')

@section('content')

    <div class="grid grid-cols-8 my-4 gap-2">
        @foreach (get_hot_posts(8) as $post)
            <div>
                <img src="https://fastly.picsum.photos/id/441/200/100.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                    alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                    class="w-full object-cover object-center block bg-gray-300" />
                <a href="/post/{{ $post['id'] }}" title="{{ $post['title'] }}">
                    <span>{{ $post['title'] }}</span>
                </a>
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
                            <img src="https://fastly.picsum.photos/id/441/800/400.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                                alt="slide 1" class="w-full h-full object-cover object-center">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img src="https://fastly.picsum.photos/id/442/800/400.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                                alt="slide 2" class="w-full h-full object-cover object-center">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img src="https://fastly.picsum.photos/id/443/800/400.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                                alt="slide 3" class="w-full h-full object-cover object-center">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 my-4 gap-2">
                @foreach (get_hot_posts(8) as $post)
                    <div>
                        <img src="https://fastly.picsum.photos/id/441/200/100.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                            alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                            class="w-full object-cover object-center block bg-gray-300" />
                        <a href="/post/{{ $post['id'] }}" title="{{ $post['title'] }}">
                            <span>{{ $post['title'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>


            <div class="mt-2 font-bold bg-gray-200 p-2 gap-4">
                @foreach (get_post_categories() as $category)
                    <a href="/posts?category_id={{ $category['id'] }}">{{ $category['name'] }}</a>
                @endforeach
            </div>


            @foreach (get_hot_posts(3) as $post)
                <div class="bg-gray-100  my-2 flex flex-row flex-wrap gap-2">
                    <img src="https://fastly.picsum.photos/id/441/200/100.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                        alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                        class="w-200px object-cover object-center block bg-gray-300">
                    <div class="py-2">
                        <a href="/post/{{ $post['id'] }}" title="{{ $post['title'] }}">
                            <h3 class="my-0 text-xl">{{ $post['title'] }}</h3>
                        </a>

                        <p class="my-0">{{ $post['content'] }}</p>
                        <!-- tags  -->
                        <div class="mt-2">
                            @foreach ($post['tags'] as $tag)
                                <a href="/posts?tag_id={{ $tag['id'] }}">
                                    <span class="bg-gray-200 px-1 py-0.5 rounded">{{ $tag['name'] }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-2">
                            <span class="text-gray">发布于:{{ $post['created_at'] }}</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="bg-gray-100 w-320px p-4">
            Hello Laravel!




            <!-- posts with pic  -->
            <h3 class="mb-1">Posts with Pic</h3>
            <div class="grid grid-cols-3 gap-1">
                @foreach (get_hot_posts(3) as $post)
                    <div>
                        <img src="https://fastly.picsum.photos/id/441/200/100.jpg?hmac=PfbiUv548vtNVDw-xxuNtmgIIjPBakQFhPCZ5FYzqvA"
                            alt="{{ $post['title'] }}" title="{{ $post['title'] }}"
                            class="w-full h-64px object-cover object-center block bg-gray-300" />
                        <a href="/post/{{ $post['id'] }}" title="{{ $post['title'] }}">
                            <span>{{ $post['title'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

            <h3 class="mb-1">Hot Posts</h3>
            @foreach (get_hot_posts(5) as $post)
                <div class="mb-2">
                    <a href="/post/{{ $post['id'] }}">
                        <span class="my-0">{{ $post['title'] }}</span>
                    </a>
                </div>
            @endforeach

            <!-- all categoies  -->
            <h3 class="mb-1">All Categories</h3>
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
