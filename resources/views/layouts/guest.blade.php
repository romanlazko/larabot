<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        {{-- <link rel="preload" as="style" href="https://14c0-2a02-8308-a006-d600-916d-cabb-e2a2-109e.eu.ngrok.io/build/assets/app-9f0db517.css"/> --}}
        {{-- <link rel="modulepreload" href="https://14c0-2a02-8308-a006-d600-916d-cabb-e2a2-109e.eu.ngrok.io/build/assets/app-96280e5c.js"/> --}}
        <link rel="stylesheet" href="https://larabot.rlbot.ru/build/assets/app-9f0db517.css"/>
        <script type="module" src="https://larabot.rlbot.ru/build/assets/app-96280e5c.js"></script>
    </head>
    {{-- <body class="font-sans text-gray-900 antialiased">
        @include('layouts.navigation')
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
           
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ ($slot) }}
            </div>
        </div>
    </body> --}}
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 ">

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <div class="flex flex-col sm:justify-center items-center">

                
                <!-- Page Content -->
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg ">
                    {{ ($slot) }}
                </div>
            </div>
        </div>
    </body>
</html>
