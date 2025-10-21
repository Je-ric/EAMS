<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EAMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            /* background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); */
            font-family: "Oswald", sans-serif;
            /* color: #fff; */
        }
    </style>

</head>
<body>
    {{-- @include('includes.links') --}}
    {{-- @include('layouts.header') --}}

    <main class="container mx-auto">
        @yield('page-content')
    </main>

    {{-- @include('layouts.footer') --}}

    @stack('scripts')
</body>
</html>
