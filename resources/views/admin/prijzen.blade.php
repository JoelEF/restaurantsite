<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijzen beheren</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 p-6">

<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Prijzen beheren</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 rounded-xl px-4 py-3 mb-6">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 rounded-xl px-4 py-3 mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Bulk percentage verhoging -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Prijzen verhogen / verlagen met percentage</h2>

        <form method="POST" action="{{ route('admin.prijzen.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="mode" id="bulk-mode" value="all">

            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="setMode('all')"
                    id="btn-all"
                    class="px-4 py-2 rounded-lg border-2 border-orange-500 bg-orange-50 text-orange-700 font-medium text-sm">
                    Alle categorieën
                </button>
                @foreach($categories as $cat)
                <button type="button" onclick="setMode('category', {{ $cat->id }})"
                    id="btn-cat-{{ $cat->id }}"
                    class="px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300">
                    {{ $cat->icon ?? '' }} {{ $cat->name }}
                </button>
                @endforeach
            </div>

            <input type="hidden" name="category_id" id="bulk-category-id" value="">

            <div class="flex items-center gap-3">
                <input type="number" name="percentage" step="0.1" placeholder="Bijv. 5 of -5"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-40 focus:ring-2 focus:ring-orange-400 outline-none">
                <span class="text-gray-500">%</span>
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2 rounded-lg transition-colors">
                    Toepassen
                </button>
            </div>
            <p class="text-xs text-gray-400">Positief getal = verhoging, negatief getal = verlaging. Prijzen worden afgerond op 2 decimalen.</p>
        </form>
    </div>

    <!-- Individuele prijzen -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Prijzen per artikel aanpassen</h2>

        <form method="POST" action="{{ route('admin.prijzen.update') }}">
            @csrf
            <input type="hidden" name="mode" value="individual">

            @foreach($categories as $category)
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 mb-3 border-b pb-2">
                    {{ $category->icon ?? '' }} {{ $category->name }}
                </h3>
                <div class="space-y-2">
                    @foreach($category->menuItems as $item)
                    <div class="flex items-center justify-between gap-4">
                        <span class="flex-1 text-sm text-gray-800">{{ $item->name }}</span>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 text-sm">€</span>
                            <input type="number" name="prices[{{ $item->id }}]"
                                value="{{ number_format($item->price, 2, '.', '') }}"
                                step="0.05" min="0"
                                class="border border-gray-300 rounded-lg px-3 py-1.5 w-24 text-right focus:ring-2 focus:ring-orange-400 outline-none text-sm">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <button type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition-colors mt-2">
                Alle prijzen opslaan
            </button>
        </form>
    </div>
</div>

<script>
    function setMode(mode, categoryId) {
        document.getElementById('bulk-mode').value = mode;
        document.getElementById('bulk-category-id').value = categoryId ?? '';

        // reset alle knoppen
        document.getElementById('btn-all').className = 'px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300';
        document.querySelectorAll('[id^="btn-cat-"]').forEach(b => {
            b.className = 'px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-600 font-medium text-sm hover:border-gray-300';
        });

        // actieve knop markeren
        const active = mode === 'all' ? 'btn-all' : 'btn-cat-' + categoryId;
        document.getElementById(active).className = 'px-4 py-2 rounded-lg border-2 border-orange-500 bg-orange-50 text-orange-700 font-medium text-sm';
    }
</script>
</body>
</html>
