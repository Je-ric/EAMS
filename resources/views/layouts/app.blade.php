<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EAMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            background: linear-gradient(135deg, #c3c3c3 0%, #fbfdff 100%);
            font-family: "Oswald", sans-serif;
        }

        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    
    <main class="flex-1 container mx-auto px-4 py-8">
        @yield('page-content')
    </main>

    @stack('scripts')
</body>
</html>
