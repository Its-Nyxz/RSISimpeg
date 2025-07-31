<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RSI SIMPEG') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- <body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
        style="background-image: url('{{ asset('img/bg-login.png') }}'); background-size: cover; background-position: center;">

        <div>
            {{-- <a href="/">
                <x-application-logo class="w-40 h-40 fill-current text-gray-500" />
            </a> --}}
            {{-- <a href="/"> --}}
                <!-- Gantikan x-application-logo dengan <img> -->
                {{-- <img src="{{ asset('img/logo.png') }}" class="w-40 h-40 fill-current text-gray-500" alt="Logo" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>  --}}

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen w-full flex items-center justify-center bg-cover bg-center"
        style="background-image: url('{{ asset('img/bg-login.png') }}');">

        <div class="w-full sm:max-w-md mx-4 p-6 bg-white rounded-lg shadow-md">
            <div class="flex justify-center mb-6">
                <a href="/">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-32 h-32">
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>

</html>
