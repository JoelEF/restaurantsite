<x-app-layout>
    <x-slot name="title">Tafel Reserveren</x-slot>

    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display text-5xl font-bold mb-4">Tafel Reserveren</h1>
            <p class="text-gray-300 text-lg">Reserveer een tafel en geniet van een onvergetelijk diner bij Pizzeria Grill Luna.</p>
        </div>
    </div>

    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <!-- Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="font-semibold text-xl mb-6">Reserveringsdetails</h2>
                        <form action="{{ route('reservation.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('name') border-red-400 @enderror">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefoon *</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('phone') border-red-400 @enderror">
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('email') border-red-400 @enderror">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Datum *</label>
                                    <input type="date" name="date" value="{{ old('date') }}" min="{{ now()->addDay()->format('Y-m-d') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('date') border-red-400 @enderror">
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tijd *</label>
                                    <select name="time" required
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('time') border-red-400 @enderror">
                                        <option value="">Kies een tijd</option>
                                        @foreach(['11:30', '12:00', '12:30', '13:00', '13:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'] as $time)
                                        <option value="{{ $time }}" {{ old('time') === $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    </select>
                                    @error('time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Aantal personen *</label>
                                    <select name="guests" required
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                                        @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>{{ $i }} {{ $i === 1 ? 'persoon' : 'personen' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Opmerkingen</label>
                                <textarea name="notes" rows="3"
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none"
                                          placeholder="Verjaardag, allergie informatie, rolstoeltoegankelijkheid, etc.">{{ old('notes') }}</textarea>
                            </div>
                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors text-lg">
                                📅 Reservering Bevestigen
                            </button>
                            <p class="text-xs text-gray-400 text-center">Wij nemen binnen 2 uur contact met u op ter bevestiging.</p>
                        </form>
                    </div>
                </div>

                <!-- Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-lg mb-4">Openingstijden</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between"><span class="text-gray-500">Ma – Do</span><span class="font-medium">11:00 – 22:00</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">Vrijdag</span><span class="font-medium">11:00 – 23:00</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">Zaterdag</span><span class="font-medium">12:00 – 23:00</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">Zondag</span><span class="font-medium">12:00 – 22:00</span></li>
                        </ul>
                    </div>
                    <div class="bg-orange-50 border border-orange-100 rounded-2xl p-6">
                        <h3 class="font-semibold text-lg mb-3">📍 Ons Adres</h3>
                        <p class="text-gray-700 text-sm mb-2">Grote Markt 42<br>1012 AB Amsterdam</p>
                        <p class="text-sm text-gray-500">Vlakbij het Centraal Station, goed bereikbaar met OV.</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                        <h3 class="font-semibold text-lg mb-3">ℹ️ Informatie</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>✅ Max. 2 uur per tafel</li>
                            <li>✅ Kindermenü beschikbaar</li>
                            <li>✅ Rolstoeltoegankelijk</li>
                            <li>✅ Gratis parkeren nabij</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
