<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
</head>

<body>
    @include('includes.dashboard.navbar')
    <main class="h-screen flex">
        @include('includes.dashboard.sidebar')
        <div id="overlay"
            class="fixed inset-0 bg-black bg-opacity-40 opacity-0 pointer-events-none transition-opacity duration-300 z-30 md:hidden">
        </div>

        <div class="w-full h-full md:w-[calc(100%-250px)] ms-auto pt-24 px-5 md:px-8 py-10">
            <div class="bg-white px-4 py-2 border border-gray-300 rounded-md w-max md:w-full">
                {{ $slot }}
            </div>
        </div>
    </main>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <script src="{{ asset('js/dashboard/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
