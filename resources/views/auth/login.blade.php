<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="w-full">
        @csrf

        <!-- Email del establecimiento -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Establecimiento</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" type="password" name="password" required
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Enlaces -->
        <div class="flex justify-between items-center text-sm text-gray-600 mb-6">
            <a href="{{ route('password.request') }}" class="hover:underline hover:text-green-600">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}" class="hover:underline hover:text-green-600">Registrarse</a>
        </div>

        <!-- Botón de Login -->
        <div>
            <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                Login
            </button>
        </div>
    </form>
</x-guest-layout>
