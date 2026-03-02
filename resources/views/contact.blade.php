<x-app-layout>
    <x-slot name="title">Contact</x-slot>

    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display text-5xl font-bold mb-4">Contact</h1>
            <p class="text-gray-300 text-lg">Heeft u een vraag of opmerking? Wij horen graag van u!</p>
        </div>
    </div>

    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <!-- Contact Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-lg mb-4">Contactgegevens</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start space-x-3">
                                <span class="text-2xl mt-0.5">📍</span>
                                <div>
                                    <p class="font-medium text-gray-900">Adres</p>
                                    <p class="text-gray-500 text-sm">Grote Markt 42<br>1012 AB Amsterdam</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <span class="text-2xl mt-0.5">📞</span>
                                <div>
                                    <p class="font-medium text-gray-900">Telefoon</p>
                                    <a href="tel:+31201234567" class="text-orange-500 hover:underline text-sm">+31 20 123 4567</a>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <span class="text-2xl mt-0.5">✉️</span>
                                <div>
                                    <p class="font-medium text-gray-900">E-mail</p>
                                    <a href="mailto:info@istanbuldoner.nl" class="text-orange-500 hover:underline text-sm">info@istanbuldoner.nl</a>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-lg mb-4">Openingstijden</h3>
                        <ul class="space-y-2 text-sm">
                            @foreach(['Ma – Do' => '11:00 – 22:00', 'Vrijdag' => '11:00 – 23:00', 'Zaterdag' => '12:00 – 23:00', 'Zondag' => '12:00 – 22:00'] as $day => $hours)
                            <li class="flex justify-between">
                                <span class="text-gray-500">{{ $day }}</span>
                                <span class="font-medium text-gray-900">{{ $hours }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Map placeholder -->
                    <div class="bg-gray-100 rounded-2xl h-48 flex items-center justify-center text-gray-400 text-center p-4">
                        <div>
                            <span class="text-4xl block mb-2">🗺️</span>
                            <p class="text-sm">Grote Markt 42, Amsterdam<br><span class="text-xs">Kaart integratie beschikbaar</span></p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="font-semibold text-xl mb-6">Stuur ons een bericht</h2>
                        <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('name') border-red-400 @enderror">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('email') border-red-400 @enderror">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Onderwerp *</label>
                                <select name="subject" required
                                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                                    <option value="">Kies een onderwerp</option>
                                    <option value="Vraag over bestelling" {{ old('subject') === 'Vraag over bestelling' ? 'selected' : '' }}>Vraag over bestelling</option>
                                    <option value="Reservering" {{ old('subject') === 'Reservering' ? 'selected' : '' }}>Reservering</option>
                                    <option value="Feedback" {{ old('subject') === 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                    <option value="Catering aanvraag" {{ old('subject') === 'Catering aanvraag' ? 'selected' : '' }}>Catering aanvraag</option>
                                    <option value="Overig" {{ old('subject') === 'Overig' ? 'selected' : '' }}>Overig</option>
                                </select>
                                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bericht *</label>
                                <textarea name="message" rows="5" required
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('message') border-red-400 @enderror"
                                          placeholder="Schrijf hier uw bericht...">{{ old('message') }}</textarea>
                                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors text-lg">
                                ✉️ Bericht Verzenden
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
