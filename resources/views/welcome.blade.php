<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World!</title>
</head>

<body>
    <h1>
        Hello World!
    </h1>
    <p>
        Welcome to my first Laravel application.
    </p>

    <!-- request  -->
     <p>
        {{ $request->input("token") }}

    </p>
    <p>
        {{ $domain }}
    </p>
    <!-- include temp  -->
    @include('layout/footer')
</body>

</html>