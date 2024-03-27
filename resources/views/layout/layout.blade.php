<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title") | {{$context['title']}}</title>
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/assets.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/typography.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/reduce-motion.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.css">  
    <script src="https://unpkg.com/swiper@8/swiper-bundle.js"> </script>  
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/unocss.css'])
  </head>

  <body class="min-h-100vh flex flex-col">
    <header class="bg-gray-200 p-1">
      <div class="main-container my-2">
        <h1 class="text-2xl mt-0 mb-0">{{$context['title']}}</h1>
        <span class="text-sm">Welcome to my first Laravel application.</span>
      </div>
     </header>

     <div class="bg-dark">
      <div class="main-container">
        <nav class="flex gap-6 py-2 flex-wrap">
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
            <h1>footer</h1>
            <p>footer</p>
        </div>
        @show
    </main>

    @include('layout/footer')
  </body>
</html>
