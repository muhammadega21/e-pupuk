<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>

<body>
    @include('includes.dashboard.navbar')
    <div id="sidebar" class="h-screen flex">
        @include('includes.dashboard.sidebar')
        <div class="mt-12">
            {{ $slot }}
        </div>
    </div>
    @stack('scripts')
</body>

</html>
