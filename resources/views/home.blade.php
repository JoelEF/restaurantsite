<x-app-layout>
    <x-slot name="title">Welkom</x-slot>

    <!-- Hero Section -->
    <section class="hero-gradient text-white relative overflow-hidden min-h-screen flex items-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 text-9xl">🥙</div>
            <div class="absolute top-40 right-20 text-7xl">🌯</div>
            <div class="absolute bottom-20 left-1/3 text-8xl">🫔</div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center bg-brand-500/20 border border-brand-500/30 rounded-full px-4 py-2 mb-6">
                        <span class="text-brand-400 text-sm font-medium">🌟 Authentiek Turks eten sinds 2005</span>
                    </div>
                    <h1 class="font-display text-5xl lg:text-7xl font-bold leading-tight mb-6">
                        De Beste
                        <span class="text-brand-400 block">Döner Kebab</span>
                        in Amsterdam
                    </h1>
                    <p class="text-gray-300 text-lg mb-8 leading-relaxed">
                        Verse ingrediënten, authentieke recepten en de warmte van Turkse gastvrijheid.
                        Bestel online of kom langs — wij zorgen voor een onvergetelijke maaltijd.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('order.index') }}"
                           class="bg-brand-500 hover:bg-brand-400 text-white font-semibold px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg shadow-brand-500/30 flex items-center space-x-2">
                            <span>🛒</span><span>Nu Bestellen</span>
                        </a>
                        <a href="{{ route('menu.index') }}"
                           class="border-2 border-white/30 hover:border-brand-400 text-white hover:text-brand-400 font-semibold px-8 py-4 rounded-full transition-all duration-300 flex items-center space-x-2">
                            <span>📋</span><span>Bekijk Menu</span>
                        </a>
                    </div>
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/10">
                        <div class="text-center">
                            <div class="font-display text-3xl font-bold text-brand-400">500+</div>
                            <div class="text-sm text-gray-400">Tevreden klanten per dag</div>
                        </div>
                        <div class="text-center">
                            <div class="font-display text-3xl font-bold text-brand-400">20+</div>
                            <div class="text-sm text-gray-400">Gerechten op het menu</div>
                        </div>
                        <div class="text-center">
                            <div class="font-display text-3xl font-bold text-brand-400">4.8★</div>
                            <div class="text-sm text-gray-400">Gemiddelde beoordeling</div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        <div class="w-96 h-96 bg-brand-500/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-brand-500/20">
                            <span class="text-9xl animate-bounce" style="animation-duration: 3s;">🥙</span>
                        </div>
                        <div class="absolute -top-4 -right-4 bg-brand-500 text-white text-xs font-bold px-3 py-2 rounded-full shadow-lg">
                            Verse bereiding!
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-green-500 text-white text-xs font-bold px-3 py-2 rounded-full shadow-lg">
                            Halal gecertificeerd ✓
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bar -->
    <section class="bg-brand-500 text-white py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">🚚</span>
                    <span class="font-medium text-sm">Bezorging vanaf €2,50</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">⏱️</span>
                    <span class="font-medium text-sm">30 min. bezorgtijd</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">✅</span>
                    <span class="font-medium text-sm">100% Halal</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">🌿</span>
                    <span class="font-medium text-sm">Verse ingrediënten</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Items -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-4xl font-bold text-gray-900 mb-4">Onze Populairste Gerechten</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Ontdek de meest geliefde gerechten van onze klanten — van klassieke döner tot heerlijke wraps.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularItems as $item)
                <div class="bg-white rounded-2xl shadow-sm card-hover overflow-hidden border border-gray-100">
                    <div class="h-48 bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center text-7xl relative">
                        {{ $item->category->icon ?? '🍽️' }}
                        @if($item->is_popular)
                        <span class="absolute top-3 right-3 badge-popular text-white text-xs font-bold px-2 py-1 rounded-full">⭐ Populair</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $item->name }}</h3>
                            <span class="text-brand-500 font-bold text-lg">€{{ number_format($item->price, 2, ',', '.') }}</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                        <div class="flex items-center space-x-2 mb-4">
                            @if($item->is_spicy) <span class="badge-spicy text-white text-xs px-2 py-1 rounded-full">🌶️ Pittig</span> @endif
                            @if($item->is_vegetarian) <span class="badge-veg text-white text-xs px-2 py-1 rounded-full">🌿 Veg</span> @endif
                        </div>
                        <button wire:click="$dispatchTo('shopping-cart', 'add-item', { menuItemId: {{ $item->id }} })"
                                class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-2 rounded-xl transition-colors flex items-center justify-center space-x-2">
                            <span>+</span><span>Toevoegen</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('menu.index') }}" class="inline-flex items-center space-x-2 bg-gray-900 hover:bg-brand-500 text-white font-semibold px-8 py-4 rounded-full transition-all duration-300">
                    <span>Heel het menu bekijken</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </section>

    <!-- About / Story -->
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="w-full h-80 bg-gradient-to-br from-brand-100 via-brand-200 to-brand-300 rounded-3xl flex items-center justify-center text-8xl shadow-xl">
                        🌯
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-brand-500 text-white p-6 rounded-2xl shadow-xl">
                        <div class="font-display text-3xl font-bold">20+</div>
                        <div class="text-sm">Jaar ervaring</div>
                    </div>
                </div>
                <div>
                    <span class="text-brand-500 font-semibold text-sm uppercase tracking-wider">Ons verhaal</span>
                    <h2 class="font-display text-4xl font-bold text-gray-900 mt-2 mb-6">Authentieke Smaak bij Pizzeria Grill Luna</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        In 2005 opende onze familie Pizzeria Grill Luna met één doel: authentieke pizza en grillgerechten brengen naar Amsterdam.
                        Met geheime recepten van onze grootmoeder en de beste ingrediënten, bereiden we elke dag vers onze gerechten met liefde.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Ons vlees is 100% halal gecertificeerd en wordt dagelijks vers gemarineerd. Van de knapperige pita tot de sappige dürüm — alles wordt met liefde en vakmanschap bereid.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                            <span class="text-2xl">🏆</span>
                            <div>
                                <div class="font-semibold text-sm">Beste Pizzeria 2024</div>
                                <div class="text-xs text-gray-500">Amsterdam Food Awards</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                            <span class="text-2xl">✅</span>
                            <div>
                                <div class="font-semibold text-sm">Halal Gecertificeerd</div>
                                <div class="text-xs text-gray-500">HVC Certificaat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-4xl font-bold text-gray-900 mb-4">Wat Onze Klanten Zeggen</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['name' => 'Fatima A.', 'review' => 'De beste döner die ik ooit heb gegeten! Het vlees is super sappig en de sauzen zijn hemels. Ik kom hier elke week!', 'rating' => 5],
                    ['name' => 'Mike van der Berg', 'review' => 'Besteld via de website, binnen 25 minuten bezorgd. Nog warm ook! De kip dürüm is absoluut aanrader.', 'rating' => 5],
                    ['name' => 'Yasmine K.', 'review' => 'Als vegetariër ben ik blij met de falafel opties. Heerlijk vers en de hummus is zelfgemaakt. Top zaak!', 'rating' => 5],
                ] as $review)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
                    <div class="flex items-center space-x-1 mb-3">
                        @for($i = 0; $i < $review['rating']; $i++) <span class="text-yellow-400">★</span> @endfor
                    </div>
                    <p class="text-gray-600 italic mb-4">"{{ $review['review'] }}"</p>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center text-brand-600 font-bold">
                            {{ substr($review['name'], 0, 1) }}
                        </div>
                        <span class="font-semibold text-gray-900">{{ $review['name'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="font-display text-4xl font-bold mb-4">Klaar om te Bestellen?</h2>
            <p class="text-gray-300 text-lg mb-8">Bestel online en geniet binnen 30 minuten van de lekkerste döner in Amsterdam. Of reserveer een tafel voor een gezellig diner.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('order.index') }}" class="bg-brand-500 hover:bg-brand-400 text-white font-semibold px-10 py-4 rounded-full transition-all duration-300 transform hover:scale-105">
                    🛒 Nu Bestellen
                </a>
                <a href="{{ route('reservation.index') }}" class="border-2 border-white/30 hover:border-white text-white font-semibold px-10 py-4 rounded-full transition-all duration-300">
                    📅 Tafel Reserveren
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
