@extends('layouts.admin')

@section('title', 'Prijzen beheren')

@section('content')
<div class="max-w-3xl space-y-8">

    <!-- Bulk percentage -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="font-semibold text-base mb-4">Verhogen / verlagen met percentage</h2>

        <form method="POST" action="{{ route('admin.prijzen.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="mode" id="bulk-mode" value="all">
            <input type="hidden" name="category_id" id="bulk-category-id" value="">

            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="setMode('all')" id="btn-all"
                    class="px-3 py-1.5 rounded-lg border-2 border-orange-500 bg-orange-50 text-orange-700 font-medium text-sm">
                    Alle categorieën
                </button>
                @foreach($categories as $cat)
                <button type="button" onclick="setMode('category', {{ $cat->id }})" id="btn-cat-{{ $cat->id }}"
                    class="px-3 py-1.5 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300">
                    {{ $cat->icon ?? '' }} {{ $cat->name }}
                </button>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <input type="number" name="percentage" step="0.1" placeholder="Bijv. 5 of -5"
                    class="border border-gray-200 rounded-lg px-4 py-2 w-36 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none text-sm">
                <span class="text-gray-500 font-medium">%</span>
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2 rounded-lg transition-colors text-sm">
                    Toepassen
                </button>
            </div>
            <p class="text-xs text-gray-400">Positief = verhoging · Negatief = verlaging</p>
        </form>
    </div>

    <!-- Individuele prijzen -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="font-semibold text-base mb-5">Prijzen per artikel</h2>

        <form method="POST" action="{{ route('admin.prijzen.update') }}">
            @csrf
            <input type="hidden" name="mode" value="individual">

            <div class="space-y-6">
                @foreach($categories as $category)
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        {{ $category->icon ?? '' }} {{ $category->name }}
                    </h3>
                    <div class="divide-y divide-gray-50">
                        @foreach($category->menuItems as $item)
                        <div class="flex items-center justify-between py-2 gap-4">
                            <span class="flex-1 text-sm text-gray-800">{{ $item->name }}</span>
                            <div class="flex items-center gap-1">
                                <span class="text-gray-400 text-sm">€</span>
                                <input type="number" name="prices[{{ $item->id }}]"
                                    value="{{ number_format($item->price, 2, '.', '') }}"
                                    step="0.05" min="0"
                                    class="border border-gray-200 rounded-lg px-3 py-1.5 w-24 text-right focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none text-sm">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit"
                class="w-full mt-6 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-colors text-sm">
                Alle prijzen opslaan
            </button>
        </form>
    </div>

</div>

<script>
    function setMode(mode, categoryId) {
        document.getElementById('bulk-mode').value = mode;
        document.getElementById('bulk-category-id').value = categoryId ?? '';

        document.getElementById('btn-all').className = 'px-3 py-1.5 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300';
        document.querySelectorAll('[id^="btn-cat-"]').forEach(b => {
            b.className = 'px-3 py-1.5 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300';
        });

        const active = mode === 'all' ? 'btn-all' : 'btn-cat-' + categoryId;
        document.getElementById(active).className = 'px-3 py-1.5 rounded-lg border-2 border-orange-500 bg-orange-50 text-orange-700 font-medium text-sm';
    }
</script>
@endsection
