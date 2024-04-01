<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title") | {{$context['title']}}</title>

    @section('head')
    @if($context['description'])
    <meta name="description" content="{{$context['description']}}" />
    @endif
    
    @if($context['keywords'])
    <meta name="keywords" content="{{$context['keywords']}}" />
    @endif

    @show
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/assets.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/typography.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/reduce-motion.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.css">  
    <script src="https://unpkg.com/swiper@8/swiper-bundle.js"> </script>  
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/unocss.css'])
  </head>

  <body class="min-h-100vh flex flex-col text-gray-800">
    <div class="bg-gray-300">
      <div class="main-container flex flex-row items-center">
        <div class="flex-1">
          <a href="/">{{$context['title']}}</a>
        </div>
        
        <div>
        {{calc_page_loading_time()}}
        </div>
      </div>
    </div>

    <header class="bg-gray-200 p-1">
      <div class="main-container my-2 flex flex-row items-end">
        <div class="flex-1 flex flex-col">
            <div class="flex flex-row">
            <h1 class="text-4xl font-bold mt-0 mb-0 text-primary">{{$context['title']}}</h1>
            </div>
            <span class="text-sm">{{$context['content']}}</span>
        </div>
        <div>
          <a href="/login">Login</a>
          <a href="/register">Register</a>
          <!-- 订阅 -->
          <a href="/subscribe">Subscribe</a>
        </div>
      </div>
     </header>

     <div class="bg-primary sticky top-0 z-10 py-2 text-lg">
      <div class="main-container">
        <nav class="flex gap-6 flex-wrap">
          @foreach(get_nav_items() as $item)
            <a href="{{$item['url']}}" class="text-white {{
                is_active_url($item['url']) ? 'underline' : ''
            }}">{{$item['title']}}</a>
          @endforeach
        </nav>
      </div>
    </div>


    <main class="flex-1 main-container">
        @section('content')
        <div>
            <h1>content</h1>
        </div>
        @show
    </main>

    @include('layout/footer')
  </body>
</html>
