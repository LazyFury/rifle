

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