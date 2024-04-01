
<h3 class="mb-2 text-primary slide-title">Hot Posts</h3>
            @foreach (get_hot_posts(5) as $post)
                <div class="mb-2">
                    <a href="/post/{{ $post['slug'] }}">
                        <span class="my-0">{{ text_cut($post['title'],20) }}</span>
                    </a>
                </div>
            @endforeach