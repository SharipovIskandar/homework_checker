<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

@include('layout.navigation')

<div class="flex">
    @include('layout.sidebar') <!-- Sidebar qo‘shildi -->

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

</body>
</html>
