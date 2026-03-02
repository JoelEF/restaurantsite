<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Pizzeria Grill Luna - Authentieke pizza en grillgerechten in uw buurt. Vers bereid met de beste ingrediënten.' }}">
    <title>{{ $title ?? 'Pizzeria Grill Luna' }} | Pizzeria Grill Luna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8ed',
                            100: '#ffefd3',
                            200: '#ffdba6',
                            300: '#ffc16d',
                            400: '#ff9d32',
                            500: '#ff7f0a',
                            600: '#e56200',
                            700: '#bf4802',
                            800: '#973a0b',
                            900: '#7a310c',
                        },
                        dark: '#1a1a1a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    @livewireStyles
    <style>
        .hero-gradient { background: linear-gradient(135deg, #1a1a1a 0%, #2d1a00 50%, #1a1a1a 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: #ff7f0a; transition: width 0.3s ease; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .badge-spicy { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        .badge-veg { background: linear-gradient(135deg, #16a34a, #15803d); }
        .badge-popular { background: linear-gradient(135deg, #d97706, #b45309); }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <!-- Navigation -->
    <nav class="bg-dark text-white sticky top-0 z-50 shadow-2xl" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center group-hover:bg-brand-400 transition-colors">
                        <span class="text-xl">🍕</span>
                    </div>
                    <div>
                        <span class="font-display text-xl font-bold text-brand-400">Pizzeria Grill</span>
                        <span class="font-display text-xl font-bold text-white ml-1">Luna</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link text-gray-300 hover:text-white transition-colors {{ request()->routeIs('home') ? 'active text-white' : '' }}">Home</a>
                    <a href="{{ route('menu.index') }}" class="nav-link text-gray-300 hover:text-white transition-colors {{ request()->routeIs('menu.*') ? 'active text-white' : '' }}">Menu</a>
                    <a href="{{ route('order.index') }}" class="nav-link text-gray-300 hover:text-white transition-colors {{ request()->routeIs('order.*') ? 'active text-white' : '' }}">Bestellen</a>
                    <a href="{{ route('reservation.index') }}" class="nav-link text-gray-300 hover:text-white transition-colors {{ request()->routeIs('reservation.*') ? 'active text-white' : '' }}">Reserveren</a>
                    <a href="{{ route('contact.index') }}" class="nav-link text-gray-300 hover:text-white transition-colors {{ request()->routeIs('contact.*') ? 'active text-white' : '' }}">Contact</a>
                </div>

                <!-- Cart + Mobile Toggle -->
                <div class="flex items-center space-x-3">
                    @livewire('shopping-cart')

                    <!-- Mobile button -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-300 hover:text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileOpen" x-transition class="md:hidden pb-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg">Home</a>
                <a href="{{ route('menu.index') }}" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg">Menu</a>
                <a href="{{ route('order.index') }}" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg">Bestellen</a>
                <a href="{{ route('reservation.index') }}" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg">Reserveren</a>
                <a href="{{ route('contact.index') }}" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 m-4 rounded-r-lg" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-green-500 mr-2">✓</span>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
        </div>
    </div>
    @endif

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-gray-400 pt-16 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center">
                            <span class="text-xl">🍕</span>
                        </div>
                        <div>
                            <span class="font-display text-xl font-bold text-brand-400">Pizzeria Grill</span>
                            <span class="font-display text-xl font-bold text-white ml-1">Luna</span>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed">Authentieke pizza en grillgerechten, bereid met de beste ingrediënten en vol smaak.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Snelle Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('menu.index') }}" class="hover:text-brand-400 transition-colors">Ons Menu</a></li>
                        <li><a href="{{ route('order.index') }}" class="hover:text-brand-400 transition-colors">Online Bestellen</a></li>
                        <li><a href="{{ route('reservation.index') }}" class="hover:text-brand-400 transition-colors">Tafel Reserveren</a></li>
                        <li><a href="{{ route('order.track') }}" class="hover:text-brand-400 transition-colors">Bestelling Volgen</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center space-x-2"><span>📍</span><span>Dorpsstraat 6/6b, 6731 AT Otterlo</span></li>
                        <li class="flex items-center space-x-2"><span>📞</span><a href="tel:+31201234567" class="hover:text-brand-400">+31 20 123 4567</a></li>
                        <li class="flex items-center space-x-2"><span>✉️</span><a href="mailto:info@pizzeriagrilluna.nl" class="hover:text-brand-400">info@pizzeriagrilluna.nl</a></li>
                    </ul>
                </div>

                <!-- Hours -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Openingstijden</h4>
                    <ul class="space-y-1 text-sm">
                        <li class="flex justify-between"><span>Maandag</span><span class="text-red-400">Gesloten</span></li>
                        <li class="flex justify-between"><span>Di – Zo</span><span class="text-white">16:00 – 20:30</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between">
                <p class="text-sm">© {{ date('Y') }} Pizzeria Grill Luna. Alle rechten voorbehouden.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors text-sm">f</a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors text-sm">ig</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
