<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BANKSACI - Bank Sampah')</title>
    
    <!-- Tailwind CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-gray-800 antialiased min-h-screen">

    <div class="flex min-h-screen">
        
        <!-- 1. SIDEBAR: Gunakan 'fixed h-screen' agar terkunci penuh dari atas sampai bawah layar -->
        <aside class="w-64 bg-[#004d39] h-screen fixed top-0 left-0 z-40 overflow-y-auto">
            @if(View::exists('layouts.sidebar'))
                @include('layouts.sidebar')
            @endif
        </aside>

        <!-- 2. MAIN CONTENT: Diberi margin kiri 'ml-64' agar tidak tertimpa sidebar -->
        <main class="flex-1 ml-64 p-8 min-h-screen bg-[#f8fafc]">
            @yield('content')
        </main>

    </div>

</body>
</html>