<div>
    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Zoek in het menu..."
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                <span class="absolute left-3 top-3.5 text-gray-400">🔍</span>
            </div>
            <div class="flex gap-2 flex-wrap">
                <button wire:click="$set('filter', 'all')" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Alles</button>
                <button wire:click="$set('filter', 'popular')" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'popular' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">⭐ Populair</button>
                <button wire:click="$set('filter', 'vegetarian')" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'vegetarian' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">🌿 Vegetarisch</button>
                <button wire:click="$set('filter', 'spicy')" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'spicy' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">🌶️ Pittig</button>
            </div>
        </div>
    </div>

    <!-- Category tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 mb-8">
        <button wire:click="$set('activeCategory', null)"
                class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ is_null($activeCategory) ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-400' }}">
            Alle categorieën
        </button>
        @foreach($categories as $category)
        <button wire:click="$set('activeCategory', {{ $category->id }})"
                class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ $activeCategory === $category->id ? 'bg-orange-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-orange-300' }}">
            {{ $category->icon }} {{ $category->name }}
        </button>
        @endforeach
    </div>

    <!-- Count -->
    <div class="text-sm text-gray-500 mb-6">
        <span wire:loading class="text-orange-500">Laden...</span>
        <span wire:loading.remove>{{ $items->count() }} gerecht{{ $items->count() !== 1 ? 'en' : '' }} gevonden</span>
    </div>

    <!-- Items Grid -->
    @if($items->isEmpty())
    <div class="text-center py-16">
        <span class="text-6xl">🔍</span>
        <h3 class="text-xl font-semibold text-gray-700 mt-4">Geen gerechten gevonden</h3>
        <p class="text-gray-500 mt-2">Probeer een andere zoekopdracht of categorie.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($items as $item)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="h-44 bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center text-6xl relative">
                {{ $item->category->icon ?? '🍽️' }}
                <div class="absolute top-2 left-2 flex flex-col gap-1">
                    @if($item->is_popular) <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">⭐ Populair</span> @endif
                    @if($item->is_spicy) <span class="bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">🌶️ Pittig</span> @endif
                    @if($item->is_vegetarian) <span class="bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">🌿 Veg</span> @endif
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="font-semibold text-gray-900 text-sm leading-tight">{{ $item->name }}</h3>
                    <span class="text-orange-500 font-bold text-sm whitespace-nowrap">€{{ number_format($item->price, 2, ',', '.') }}</span>
                </div>
                @if($item->description)
                <p class="text-gray-500 text-xs mb-3 line-clamp-2">{{ $item->description }}</p>
                @endif
                <button wire:click="$dispatchTo('shopping-cart', 'add-item', { menuItemId: {{ $item->id }} })"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 rounded-xl transition-colors flex items-center justify-center space-x-1">
                    <span>+</span><span>Toevoegen</span>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
