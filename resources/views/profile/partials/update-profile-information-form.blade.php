<section>
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Actualiza la información de tu establecimiento y tu correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Establecimiento</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-600">
                        Tu email no está verificado.
                        <button form="send-verification" class="text-green-600 underline text-sm hover:text-green-700">
                            Haz clic aquí para reenviar el email de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            Se ha enviado un nuevo enlace de verificación a tu dirección de email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end pt-3">
            <button type="submit"
                    class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                Guardar
            </button>

            @if (session('status') === 'profile-updated')
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
