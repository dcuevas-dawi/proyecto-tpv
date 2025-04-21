<section>
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Actualizar Contraseña
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Asegúrate de usar una contraseña larga y segura para proteger tu cuenta.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                   autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
            <input id="update_password_password" name="password" type="password"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-3">
            <button type="submit"
                    class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                Guardar
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="ml-3 text-sm font-medium text-green-600"
                >¡Guardado!</span>
            @endif
        </div>
    </form>
</section>
