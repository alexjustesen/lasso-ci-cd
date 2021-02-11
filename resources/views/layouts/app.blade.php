<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        {{-- Favicon --}}
        <x-favicon/>

        {{-- Site title and description --}}
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="Laravel CI/CD pipeline with Lasso and GitHub actions.">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        {{-- Styles --}}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>

    <body class="font-sans antialiased bg-gray-800">
        <div class="min-h-screen">
            {{ $slot }}
        </div>

        {{-- Scripts --}}
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
