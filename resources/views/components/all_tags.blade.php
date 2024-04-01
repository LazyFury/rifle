
<h3 class="mb-2 text-primary slide-title">All Tags</h3>
<div class="flex flex-row flex-wrap gap-2">
    @foreach (get_all_tags() as $tag)
        <div class="bg-gray-200 rounded px-2 py-0.5">
            <a href="/posts?tag_id={{ $tag->id }}">
                <span>{{ $tag->name }}</span>
            </a>
        </div>
    @endforeach
</div>