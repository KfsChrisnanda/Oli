<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Point of Sale (POS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Ini adalah cara memanggil komponen Livewire ke dalam halaman --}}
            <livewire:pos />
        </div>
    </div>
</x-app-layout>