<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shop') - Payment Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts & Styles -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/shop.js'])

    @stack('styles')
</head>
<body class="shop-layout">
    <!-- Header -->
    @include('components.shop.header')

    <!-- Main Content -->
    <main class="shop-main">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.shop.footer')

    @stack('scripts')
</body>
</html>
