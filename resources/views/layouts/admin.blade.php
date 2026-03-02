<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Beheer' }} | Pizzeria Grill Luna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-56 bg-gray-900 text-white flex flex-col sticky top-0 h-screen">
        <div class="p-5 border-b border-gray-800">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-sm">🍕</div>
                <span class="font-bold text-orange-400 text-sm">Grill Luna</span>
            </a>
            <p class="text-gray-500 text-xs mt-1">Beheer</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.prijzen') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.prijzen*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span>💰</span><span>Prijzen</span>
            </a>
            <a href="{{ route('kitchen') }}"
               class="flex items-center space-x-2.5 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('kitchen*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <span>🍽️</span><span>Keuken display</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 text-gray-400 hover:text-white text-sm transition-colors">
                <span>←</span><span>Naar website</span>
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b border-gray-200 px-8 py-4">
            <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Beheer')</h1>
        </header>

        @if(session('success'))
        <div class="mx-8 mt-5 bg-green-50 border border-green-300 text-green-800 rounded-xl px-5 py-3 text-sm">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-8 mt-5 bg-red-50 border border-red-300 text-red-800 rounded-xl px-5 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
