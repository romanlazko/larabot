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
        {{-- <link rel="preload" as="style" href="https://14c0-2a02-8308-a006-d600-916d-cabb-e2a2-109e.eu.ngrok.io/build/assets/app-9f0db517.css"/>
        <link rel="modulepreload" href="https://14c0-2a02-8308-a006-d600-916d-cabb-e2a2-109e.eu.ngrok.io/build/assets/app-96280e5c.js"/> --}}
        {{-- <link rel="stylesheet" href="https://f0fb-2a02-8308-a006-d600-9814-56f0-5a35-f0bb.eu.ngrok.io/build/assets/app-9f0db517.css"/>
        <script type="module" src="https://f0fb-2a02-8308-a006-d600-9814-56f0-5a35-f0bb.eu.ngrok.io/build/assets/app-96280e5c.js"></script> --}}
        <link rel="stylesheet" href="https://larabot.rlbot.ru/public/build/assets/app-9f0db517.css"/>
        <script type="module" src="https://larabot.rlbot.ru/public/build/assets/app-96280e5c.js"></script>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/f4c6764ec6.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </head>
    <body x-data="{ open: false }"  class="font-sans antialiased max-h-screen bg-red-500">
        {{-- <div  class="fixed w-full z-10 top-0 sm:hidden"> 
            @include('layouts.navigation')
        </div> --}}
        {{-- <div class="min-h-screen bg-gray-100">
            

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            @yield('content')
        </div> --}}
        <div :class="{'z-50': open, 'z-10': ! open}" class="sm:w-1/6 sm:flex sm:flex-col w-full bg-white shadow fixed overflow-auto sm:h-screen sm:p-0">
            @include('layouts.sidebar')
        </div>
        
        {{-- <div class="bg-gray-100 w-full h-screen flex fixed overflow-auto" style="padding-top: 4rem"> --}}
            <!-- Sidebar -->
            
 
        <div class="sm:w-5/6 sm:flex sm:flex-col bg-gray-100 ml-auto pt-16 min-h-screen sm:p-0">
            @yield('content')
        </div>
        {{-- </div> --}}
        
    </body>
    @yield('script')
</html>
