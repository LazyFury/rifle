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