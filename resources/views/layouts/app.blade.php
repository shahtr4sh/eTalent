<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Permohonan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
<!-- Navigation Bar Simple -->
<div class="flex space-x-8">
    <a href="{{ route('app.permohonan.index') }}"
       class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300">
        Permohonan
    </a>

    <a href="{{ route('app.profil') }}"
       class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300">
        Profil
    </a>
</div>

<div class="flex items-center">
    <form method="POST" action="{{ route('app.logout') }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
            Logout
        </button>
    </form>
</div>

<!-- Page Content -->
<main class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</main>

<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>
