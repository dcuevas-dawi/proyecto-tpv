<x-app-layout>
    <div class="min-h-full flex flex-col items-center justify-center">
        <div class="text-center max-w-4xl mx-auto px-6">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('favicon.png') }}" alt="Logo" class="h-48 w-auto mx-auto">
            </div>

            <!-- App Name -->
            <h1 class="text-5xl font-bold text-primary mb-6">
                {{ config('app.name') }}
            </h1>

            <!-- Welcome message -->
            <p class="text-2xl text-gray-600 mb-8">
                Sistema de gestión para tu negocio
            </p>

            <!-- Clock -->
            <div class="bg-white p-6 rounded-xl shadow-md mb-8">
                <div id="current-time" class="text-4xl font-bold text-gray-800"></div>
                <div id="current-date" class="text-xl text-gray-600 mt-2"></div>
            </div>

            <!-- Message -->
            <p class="text-gray-500 mt-6">
                Utiliza las opciones del menú lateral para navegar por la aplicación
            </p>

            <!-- Version -->
            <div class="mt-12 text-sm text-gray-400">
                Versión 1.0.0
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/menu.js') }}"></script>
    @endpush
</x-app-layout>
