<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        ¿Olvidaste tu contraseña? Introduce tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="w-full">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center text-sm text-gray-600 mb-6">
            <a href="{{ route('login') }}" class="hover:underline hover:text-green-600">Volver al inicio de sesión</a>
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                Enviar enlace de recuperación
            </button>
        </div>
    </form>
</x-guest-layout>
