<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hello World!</title>
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/assets.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/typography.css" rel="stylesheet" />
    <link href="https://unpkg.com/sanitize.css/reduce-motion.css" rel="stylesheet" />
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/unocss.css'])
  </head>

  <body class="">
    <header class="bg-gray-200 p-1 px-4">
      <div class="main-container">
        <h1 class="text-2xl">Hello World!</h1>
      </div>
     </header>
    <main class="min-h-60vh main-container">
      <p>Welcome to my first Laravel application.</p>
    </main>

    
    @include('layout/footer')
  </body>
</html>
