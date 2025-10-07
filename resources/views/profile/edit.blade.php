<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    Profile Settings
                </h2>
                <p class="text-gray-300 text-sm mt-1">Kelola informasi akun dan keamanan Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 shadow-lg sm:rounded-xl border border-gray-600/40 p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 shadow-lg sm:rounded-xl border border-gray-600/40 p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 shadow-lg sm:rounded-xl border border-gray-600/40 p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
