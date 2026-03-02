<x-app-layout>
    <x-slot name="title">Online Bestellen</x-slot>

    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display text-5xl font-bold mb-4">Online Bestellen</h1>
            <p class="text-gray-300 text-lg">Bestel eenvoudig online — bezorging of afhalen, u kiest zelf!</p>
        </div>
    </div>

    <section class="py-12" x-data="{
        cart: {{ json_encode(session()->get('cart', [])) }},
        orderType: 'pickup',
        step: 1,
        get subtotal() { return Object.values(this.cart).reduce((s, i) => s + i.price * i.quantity, 0) },
        get deliveryFee() { return this.orderType === 'delivery' ? 2.50 : 0 },
        get total() { return this.subtotal + this.deliveryFee },
        formatEuro(v) { return '€' + v.toFixed(2).replace('.', ',') }
    }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Menu snel kiezen -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Type selector -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="font-semibold text-lg mb-4">Hoe wilt u uw bestelling ontvangen?</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="orderType = 'pickup'" :class="orderType === 'pickup' ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200 text-gray-600 hover:border-gray-300'" class="border-2 rounded-xl p-4 flex flex-col items-center space-y-2 transition-colors">
                                <span class="text-3xl">🏃</span>
                                <span class="font-semibold">Afhalen</span>
                                <span class="text-xs text-gray-500">Gratis · 15-20 min</span>
                            </button>
                            <button @click="orderType = 'delivery'" :class="orderType === 'delivery' ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200 text-gray-600 hover:border-gray-300'" class="border-2 rounded-xl p-4 flex flex-col items-center space-y-2 transition-colors">
                                <span class="text-3xl">🚚</span>
                                <span class="font-semibold">Bezorging</span>
                                <span class="text-xs text-gray-500">€2,50 · 30-45 min</span>
                            </button>
                        </div>
                    </div>

                    <!-- Order Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="font-semibold text-lg mb-4">Uw Gegevens</h2>
                        <form action="{{ route('order.store') }}" method="POST" id="orderForm"
                              @submit="Object.entries(cart).forEach(([id, item], i) => {
                                  let el = document.getElementById('cartInputs');
                                  el.innerHTML += '<input type=\'hidden\' name=\'items[\'+i+\'][id]\' value=\''+item.id+'\'><input type=\'hidden\' name=\'items[\'+i+\'][quantity]\' value=\''+item.quantity+'\'>';
                              })">
                            @csrf
                            <input type="hidden" name="type" :value="orderType">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('customer_name') border-red-400 @enderror">
                                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefoon *</label>
                                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('customer_phone') border-red-400 @enderror">
                                    @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none @error('customer_email') border-red-400 @enderror">
                                    @error('customer_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div x-show="orderType === 'delivery'" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bezorgadres *</label>
                                <textarea name="delivery_address" rows="2"
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none"
                                          placeholder="Straatnaam 1, 1234 AB Amsterdam">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Opmerkingen</label>
                                <textarea name="notes" rows="2"
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none"
                                          placeholder="Extra saus, allergie informatie, etc.">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Hidden cart items - populated by JS -->
                            <div id="cartInputs"></div>

                            @if($errors->has('items'))
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                <p class="text-red-600 text-sm">⚠️ Voeg minstens één item toe aan uw bestelling.</p>
                            </div>
                            @endif

                            <template x-if="Object.keys(cart).length === 0">
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 text-center">
                                    <p class="text-amber-700 text-sm">Uw winkelwagen is leeg. <a href="{{ route('menu.index') }}" class="font-semibold text-orange-600 hover:underline">Bekijk ons menu →</a></p>
                                </div>
                            </template>

                            <template x-if="Object.keys(cart).length > 0">
                                <button type="submit"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors text-lg flex items-center justify-center space-x-2">
                                    <span>✓</span><span>Bestelling Plaatsen · <span x-text="formatEuro(total)"></span></span>
                                </button>
                            </template>
                        </form>
                    </div>
                </div>

                <!-- Right: Cart summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-20">
                        <h2 class="font-semibold text-lg mb-4">Uw Bestelling</h2>

                        <template x-if="Object.keys(cart).length === 0">
                            <div class="text-center py-8">
                                <span class="text-4xl">🛒</span>
                                <p class="text-gray-500 text-sm mt-2">Winkelwagen is leeg</p>
                                <a href="{{ route('menu.index') }}" class="text-orange-500 hover:underline text-sm mt-2 inline-block">Menu bekijken →</a>
                            </div>
                        </template>

                        <template x-if="Object.keys(cart).length > 0">
                            <div class="space-y-3">
                                <template x-for="(item, id) in cart" :key="id">
                                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500" x-text="formatEuro(item.price) + ' per stuk'"></p>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-3">
                                            <span class="text-xs text-gray-600" x-text="'×' + item.quantity"></span>
                                            <span class="text-sm font-semibold text-orange-500" x-text="formatEuro(item.price * item.quantity)"></span>
                                        </div>
                                    </div>
                                </template>

                                <div class="pt-2 space-y-2">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Subtotaal</span>
                                        <span x-text="formatEuro(subtotal)"></span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Bezorgkosten</span>
                                        <span x-text="orderType === 'delivery' ? formatEuro(deliveryFee) : 'Gratis'"></span>
                                    </div>
                                    <div class="flex justify-between font-bold text-base border-t pt-2">
                                        <span>Totaal</span>
                                        <span class="text-orange-500" x-text="formatEuro(total)"></span>
                                    </div>
                                </div>

                                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-center">
                                    <p class="text-green-700 text-xs font-medium">
                                        <span x-show="orderType === 'pickup'">✓ Klaar in 15-20 minuten</span>
                                        <span x-show="orderType === 'delivery'">🚚 Bezorging in 30-45 minuten</span>
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
