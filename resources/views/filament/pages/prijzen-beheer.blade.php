<x-filament-panels::page>

    {{-- Bulk percentage --}}
    <x-filament::section heading="Verhogen / verlagen met percentage">
        <div class="space-y-4">

            <div class="flex flex-wrap gap-2">
                <x-filament::button
                    wire:click="$set('mode', 'all')"
                    :color="$mode === 'all' ? 'primary' : 'gray'"
                    size="sm">
                    Alle categorieën
                </x-filament::button>

                @foreach($this->getCategories() as $cat)
                <x-filament::button
                    wire:click="$set('mode', 'category'); $set('categoryId', {{ $cat->id }})"
                    :color="$mode === 'category' && $categoryId == $cat->id ? 'primary' : 'gray'"
                    size="sm">
                    {{ $cat->icon ?? '' }} {{ $cat->name }}
                </x-filament::button>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <x-filament::input.wrapper prefix="%" class="w-36">
                        <x-filament::input
                            type="number"
                            wire:model="percentage"
                            step="0.1"
                            placeholder="Bijv. 5 of -5" />
                    </x-filament::input.wrapper>
                </div>
                <x-filament::button wire:click="applyPercentage" color="warning">
                    Toepassen
                </x-filament::button>
            </div>

            <p class="text-xs text-gray-400 dark:text-gray-500">
                Positief = verhoging &middot; Negatief = verlaging
            </p>
        </div>
    </x-filament::section>

    {{-- Individuele prijzen --}}
    <x-filament::section heading="Prijzen per artikel">
        <div class="space-y-6">
            @foreach($this->getCategories() as $category)
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
                    {{ $category->icon ?? '' }} {{ $category->name }}
                </p>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach($category->menuItems as $item)
                    <div class="flex items-center justify-between py-2 gap-4">
                        <span class="flex-1 text-sm">{{ $item->name }}</span>
                        <x-filament::input.wrapper prefix="€" class="w-28">
                            <x-filament::input
                                type="number"
                                wire:model="prices.{{ $item->id }}"
                                step="0.05"
                                min="0" />
                        </x-filament::input.wrapper>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            <x-filament::button wire:click="savePrices" color="primary" class="w-full">
                Alle prijzen opslaan
            </x-filament::button>
        </div>
    </x-filament::section>

</x-filament-panels::page>
