<div x-data="{ cartOpen: @entangle('isOpen').live }">
    <!-- Cart Toggle Button -->
    <button @click="cartOpen = !cartOpen"
            class="relative bg-orange-500 hover:bg-orange-400 text-white p-2 rounded-full transition-colors">
        🛒
        @if($this->totalItems() > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold">
            {{ $this->totalItems() }}
        </span>
        @endif
    </button>

    <!-- Cart Drawer -->
    <div x-show="cartOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 flex flex-col">

        <!-- Header -->
        <div class="bg-gray-900 text-white p-4 flex items-center justify-between">
            <h2 class="font-semibold text-lg">🛒 Mijn Bestelling</h2>
            <button @click="cartOpen = false" class="text-gray-400 hover:text-white">✕</button>
        </div>

        <!-- Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @if(empty($cart))
            <div class="text-center py-12">
                <span class="text-5xl">🛒</span>
                <p class="text-gray-500 mt-3">Je winkelwagen is leeg</p>
                <button @click="cartOpen = false" class="mt-4 text-orange-500 hover:underline text-sm">Bekijk het menu</button>
            </div>
            @else
            @foreach($cart as $id => $item)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm text-gray-900 truncate">{{ $item['name'] }}</p>
                    <p class="text-orange-500 text-sm font-semibold">€{{ number_format($item['price'], 2, ',', '.') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button wire:click="decreaseQuantity({{ $id }})" class="w-7 h-7 bg-gray-200 hover:bg-gray-300 rounded-full text-sm font-bold flex items-center justify-center transition-colors">-</button>
                    <span class="font-bold text-sm w-5 text-center">{{ $item['quantity'] }}</span>
                    <button wire:click="addItem({{ $id }})" class="w-7 h-7 bg-orange-500 hover:bg-orange-600 text-white rounded-full text-sm font-bold flex items-center justify-center transition-colors">+</button>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <!-- Footer -->
        @if(!empty($cart))
        <div class="border-t p-4 space-y-3">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotaal</span>
                <span>€{{ number_format($this->subtotal(), 2, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Bezorgkosten</span>
                <span class="text-green-600">Berekend bij bestelling</span>
            </div>
            <div class="flex justify-between font-bold text-lg border-t pt-2">
                <span>Totaal</span>
                <span class="text-orange-500">€{{ number_format($this->subtotal(), 2, ',', '.') }}</span>
            </div>
            <a href="{{ route('order.index') }}"
               class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl text-center transition-colors">
                Afrekenen →
            </a>
            <button wire:click="clearCart" class="w-full text-gray-400 hover:text-red-500 text-sm transition-colors">
                Winkelmand legen
            </button>
        </div>
        @endif
    </div>

    <!-- Overlay -->
    <div x-show="cartOpen"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="cartOpen = false"
         class="fixed inset-0 bg-black/50 z-40"></div>
</div>
