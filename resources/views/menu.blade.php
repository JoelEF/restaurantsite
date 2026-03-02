<x-app-layout>
    <x-slot name="title">Menu</x-slot>

    <!-- Header -->
    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-display text-5xl font-bold mb-4">Ons Menu</h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">Van klassieke döner tot verse wraps en zelfgemaakte bijgerechten — alles dagelijks vers bereid.</p>
        </div>
    </div>

    <!-- Menu with Livewire Filter -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('menu-filter')
        </div>
    </section>
</x-app-layout>
