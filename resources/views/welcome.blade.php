<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-secondary-100 dark:bg-secondary-900 text-secondary-800 dark:text-secondary-200">
        <div class="container mx-auto px-4 py-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-primary-600">{{ config('app.name', 'Laravel') }}</h1>
                <nav>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <main class="bg-white dark:bg-secondary-800 shadow-lg rounded-lg p-8">
                <div class="text-center">
                    <h2 class="mb-4">Welcome to Application</h2>
                    <p class="mb-8">This is a starting point for enterprise SaaS product. We've set up a professional design system to get you started.</p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ url('/read-the-docs.html') }}" target="_blank" class="btn btn-primary">Read the Docs</a>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>