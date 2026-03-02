<x-app-layout>
    <x-slot name="title">Bestelling Volgen</x-slot>

    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display text-5xl font-bold mb-4">Bestelling Volgen</h1>
            <p class="text-gray-300 text-lg">Voer uw bestelnummer in om de status te zien.</p>
        </div>
    </div>

    <section class="py-12">
        <div class="max-w-xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                <form method="GET" action="{{ route('order.track') }}" class="flex gap-3">
                    <input type="text" name="order_number" value="{{ request('order_number') }}"
                           placeholder="bijv. IST-ABC123"
                           class="flex-1 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                        Zoeken
                    </button>
                </form>
            </div>

            @if($order)
            @php
                $statuses = [
                    'pending' => ['label' => 'In behandeling', 'icon' => '📋', 'step' => 1],
                    'confirmed' => ['label' => 'Bevestigd', 'icon' => '✅', 'step' => 2],
                    'preparing' => ['label' => 'In bereiding', 'icon' => '👨‍🍳', 'step' => 3],
                    'ready' => ['label' => 'Klaar', 'icon' => '🎉', 'step' => 4],
                    'delivered' => ['label' => 'Bezorgd/Afgehaald', 'icon' => '🏠', 'step' => 5],
                ];
                $currentStep = $statuses[$order->status]['step'] ?? 1;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-bold text-xl text-gray-900">{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d-m-Y H:i') }}</p>
                    </div>
                    <span class="text-2xl font-bold text-orange-500">€{{ number_format($order->total, 2, ',', '.') }}</span>
                </div>

                <!-- Status Steps -->
                <div class="relative mb-8">
                    <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200"></div>
                    <div class="absolute top-5 left-0 h-0.5 bg-orange-500 transition-all duration-500" style="width: {{ (($currentStep - 1) / 4) * 100 }}%"></div>
                    <div class="relative flex justify-between">
                        @foreach($statuses as $key => $status)
                        @if($key !== 'cancelled')
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg border-2 {{ $currentStep >= $status['step'] ? 'bg-orange-500 border-orange-500 text-white' : 'bg-white border-gray-200 text-gray-400' }} z-10">
                                {{ $currentStep >= $status['step'] ? $status['icon'] : $status['step'] }}
                            </div>
                            <p class="text-xs text-center mt-2 {{ $currentStep >= $status['step'] ? 'text-orange-600 font-semibold' : 'text-gray-400' }} max-w-16">{{ $status['label'] }}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                @if($order->status === 'cancelled')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center text-red-600">
                    ❌ Deze bestelling is geannuleerd. Neem contact met ons op voor meer informatie.
                </div>
                @endif
            </div>

            @elseif(request('order_number'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <span class="text-4xl">❓</span>
                <p class="text-red-600 font-semibold mt-3">Bestelling niet gevonden</p>
                <p class="text-red-500 text-sm mt-1">Controleer uw bestelnummer en probeer opnieuw.</p>
            </div>
            @endif
        </div>
    </section>
</x-app-layout>
