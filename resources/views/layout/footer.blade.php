<footer class="min-h-120px bg-gray-200">
    <div class="main-container py-4 px-2 flex flex-col gap-2">
        
    <div>
        {{$context['icp']}}
    </div>

    <div>
    <!-- var context  -->
    <span>
        title:{{ $context['title'] }}
    </span>
    <span>
        content:{{ $context['content'] }}
    </span>
    </div>

    <div class="friends">
        <!-- var friendLinks -->
        @foreach($friendLinks as $link)
            <a href="{{ $link['url'] }}" target="_blank">{{ $link['name'] }}</a>
        @endforeach
    </div>

    <div>
        <script>
            {!! $context['js'] !!}
        </script>
    </div>
</div>
</footer>