<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" darkTheme="black" lightTheme="wireframe">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
    {{ $slot }}
</div>
</body>
</html>
