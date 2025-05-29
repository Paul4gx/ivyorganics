<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Ivy Organics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{asset('images/favicon.png')}}">
</head>
<body class="font-poppins bg-ivory text-gray-800">
    <!-- Navigation -->
    @include('components.navbar')
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    <!-- Footer -->
    @include('components.footer')
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>