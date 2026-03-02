<x-app-layout>
    <x-slot name="title">Bestelling Bevestigd</x-slot>

    <style>
        @media print {
            body * { visibility: hidden; }
            #keukenbon, #keukenbon * { visibility: visible; }
            #keukenbon {
                position: fixed;
                top: 0; left: 0;
                width: 80mm;
                font-family: monospace;
                font-size: 12pt;
                color: #000;
            }
        }
    </style>

    <!-- Keukenbon (alleen zichtbaar bij printen) -->
    <div id="keukenbon" style="display:none;">
        <div style="text-align:center; border-bottom:2px dashed #000; padding-bottom:8px; margin-bottom:8px;">
            <strong style="font-size:16pt;">KEUKENBON</strong><br>
            <span style="font-size:10pt;">{{ now()->format('d-m-Y H:i') }}</span>
        </div>
        <div style="margin-bottom:8px;">
            <strong>#{{ $order->order_number }}</strong><br>
            Naam: {{ $order->customer_name }}<br>
            Tel: {{ $order->customer_phone }}<br>
            Type: {{ $order->type === 'delivery' ? 'BEZORGING' : 'AFHALEN' }}
            @if($order->type === 'delivery')
            <br>Adres: {{ $order->delivery_address }}
            @endif
        </div>
        <div style="border-top:1px dashed #000; padding-top:8px; margin-bottom:8px;">
            @foreach($order->items as $item)
            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                <span><strong>{{ $item->quantity }}x</strong> {{ $item->name }}</span>
            </div>
            @endforeach
        </div>
        @if($order->notes)
        <div style="border-top:1px dashed #000; padding-top:8px; margin-bottom:8px; font-size:10pt;">
            <strong>Opmerking:</strong><br>{{ $order->notes }}
        </div>
        @endif
        <div style="border-top:2px dashed #000; padding-top:8px; text-align:center; font-size:10pt;">
            {{ $order->type === 'delivery' ? '🚚 Bezorging 30-45 min' : '🏃 Afhalen 15-20 min' }}
        </div>
    </div>

    <section class="py-20">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-12">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl">✅</span>
                </div>
                <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">Bestelling Ontvangen!</h1>
                <p class="text-gray-500 mb-8">Bedankt voor uw bestelling, {{ $order->customer_name }}!</p>

                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6 mb-8">
                    <p class="text-sm text-gray-500 mb-1">Bestelnummer</p>
                    <p class="font-display text-2xl font-bold text-orange-500">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-400 mt-1">Gebruik dit nummer om uw bestelling te volgen</p>
                </div>

                <div class="space-y-3 text-left mb-8">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <div>
                            <span class="font-medium text-gray-900">{{ $item->name }}</span>
                            <span class="text-gray-400 text-sm ml-2">×{{ $item->quantity }}</span>
                        </div>
                        <span class="font-semibold text-orange-500">€{{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between font-bold text-lg pt-2">
                        <span>Totaal</span>
                        <span class="text-orange-500">€{{ number_format($order->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 text-sm text-blue-700">
                    @if($order->type === 'delivery')
                    🚚 Uw bestelling wordt bezorgd naar <strong>{{ $order->delivery_address }}</strong><br>
                    Verwachte bezorgtijd: <strong>30-45 minuten</strong>
                    @else
                    🏃 Uw bestelling ligt klaar om af te halen bij:<br>
                    <strong>Dorpsstraat 6/6b, 6731 AT Otterlo</strong><br>
                    Verwachte bereidingstijd: <strong>15-20 minuten</strong>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('order.track') }}?order_number={{ $order->order_number }}"
                       class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition-colors text-center">
                        📍 Volg uw bestelling
                    </a>
                    <a href="{{ route('home') }}"
                       class="flex-1 border border-gray-200 hover:border-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition-colors text-center">
                        Terug naar home
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</x-app-layout>
