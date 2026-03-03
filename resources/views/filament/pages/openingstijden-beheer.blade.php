<x-filament-panels::page>

    {{-- ===================== OPENINGSTIJDEN ===================== --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Openingstijden per dag</h2>

        <div class="space-y-3">
            @php
                $dayNames = ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag'];
            @endphp

            @foreach($dayNames as $i => $name)
                <div class="flex items-center gap-4 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    {{-- Dag naam --}}
                    <span class="w-28 font-medium text-gray-700 dark:text-gray-300">{{ $name }}</span>

                    {{-- Open toggle --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               wire:model="hours.{{ $i }}.is_open"
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Open</span>
                    </label>

                    {{-- Tijden --}}
                    <div class="flex items-center gap-2 {{ $hours[$i]['is_open'] ? '' : 'opacity-40 pointer-events-none' }}">
                        <input type="time"
                               wire:model="hours.{{ $i }}.open_time"
                               class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-2 py-1">
                        <span class="text-gray-500">–</span>
                        <input type="time"
                               wire:model="hours.{{ $i }}.close_time"
                               class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-2 py-1">
                    </div>

                    @if(!$hours[$i]['is_open'])
                        <span class="text-xs text-red-500 font-medium">GESLOTEN</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button wire:click="saveHours"
                    class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center gap-1.5 font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-btn-color-primary fi-color-primary bg-primary-600 text-white hover:bg-primary-500 px-4 py-2 rounded-lg text-sm">
                Openingstijden opslaan
            </button>
        </div>
    </div>

    {{-- ===================== SLUITINGSDAGEN / FEESTDAGEN ===================== --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sluitingsdagen & Feestdagen</h2>

        {{-- Bestaande feestdagen --}}
        @php $holidays = $this->getHolidays(); @endphp

        @if($holidays->count())
            <table class="w-full text-sm mb-6">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-2">Datum</th>
                        <th class="pb-2">Naam</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($holidays as $h)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 text-gray-800 dark:text-gray-200 font-medium">
                                {{ \Carbon\Carbon::parse($h->date)->format('d-m-Y') }}
                                <span class="text-xs text-gray-400 ml-1">
                                    ({{ \Carbon\Carbon::parse($h->date)->locale('nl')->isoFormat('dddd') }})
                                </span>
                            </td>
                            <td class="py-2 text-gray-700 dark:text-gray-300">{{ $h->name ?: '–' }}</td>
                            <td class="py-2">
                                @if($h->is_closed)
                                    <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-medium">Gesloten</span>
                                @else
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">
                                        {{ substr($h->open_time,0,5) }} – {{ substr($h->close_time,0,5) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 text-right">
                                <button wire:click="deleteHoliday({{ $h->id }})"
                                        wire:confirm="Weet je zeker dat je dit wilt verwijderen?"
                                        class="text-red-500 hover:text-red-700 text-xs underline">
                                    Verwijder
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-400 text-sm mb-6">Geen sluitingsdagen ingesteld.</p>
        @endif

        {{-- Nieuwe feestdag toevoegen --}}
        <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Sluitingsdag toevoegen</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Datum *</label>
                <input type="date" wire:model="holidayDate"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Naam (optioneel)</label>
                <input type="text" wire:model="holidayName" placeholder="bijv. Kerst, Vakantie..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2">
            </div>
            <div class="flex items-center gap-3 pt-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="holidayIsClosed"
                           class="rounded border-gray-300 text-primary-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Geheel gesloten</span>
                </label>
            </div>
            <div class="{{ $holidayIsClosed ? 'opacity-40 pointer-events-none' : '' }}">
                <label class="block text-xs text-gray-500 mb-1">Afwijkende tijden</label>
                <div class="flex items-center gap-2">
                    <input type="time" wire:model="holidayOpenTime"
                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-2 py-2">
                    <span class="text-gray-500 text-sm">–</span>
                    <input type="time" wire:model="holidayCloseTime"
                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-2 py-2">
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button wire:click="addHoliday"
                    class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center gap-1.5 font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-btn-color-primary fi-color-primary bg-primary-600 text-white hover:bg-primary-500 px-4 py-2 rounded-lg text-sm">
                Sluitingsdag toevoegen
            </button>
        </div>
    </div>

</x-filament-panels::page>
