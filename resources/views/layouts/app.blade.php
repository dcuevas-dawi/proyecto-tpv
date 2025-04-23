<!-- Layout for registered users, main application layout -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen w-screen bg-gray-100">
            <!-- Aside/Sidebar -->
            <aside class="w-[10%] h-full bg-white border-r border-gray-200 overflow-y-auto">
                @include('layouts.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="w-[90%] h-full flex flex-col">
                <!-- Top Navigation -->
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-6">
                    @isset($header)
                        <header class="mb-6">
                            <h1 class="text-2xl font-semibold text-gray-800">
                                {{ $header }}
                            </h1>
                        </header>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
