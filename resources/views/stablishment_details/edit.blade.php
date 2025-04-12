<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Datos del Establecimiento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('stablishment_details.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic info -->
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium mb-4">Información Básica</h3>

                            <div class="mb-4">
                                <x-input-label for="commercial_name" :value="__('Nombre Comercial')" />
                                <x-text-input id="commercial_name" class="block mt-1 w-full" type="text" name="commercial_name" :value="old('commercial_name', $stablishmentDetails->commercial_name)" />
                                <x-input-error :messages="$errors->get('commercial_name')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="legal_name" :value="__('Nombre Legal')" />
                                    <x-text-input id="legal_name" class="block mt-1 w-full" type="text" name="legal_name" :value="old('legal_name', $stablishmentDetails->legal_name)" />
                                    <x-input-error :messages="$errors->get('legal_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="cif" :value="__('CIF/NIF')" />
                                    <x-text-input id="cif" class="block mt-1 w-full" type="text" name="cif" :value="old('cif', $stablishmentDetails->cif)" />
                                    <x-input-error :messages="$errors->get('cif')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Adress -->
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium mb-4">Dirección</h3>

                            <div class="mb-4">
                                <x-input-label for="address" :value="__('Dirección')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $stablishmentDetails->address)" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="postal_code" :value="__('Código Postal')" />
                                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $stablishmentDetails->postal_code)" />
                                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="city" :value="__('Ciudad')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $stablishmentDetails->city)" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="province" :value="__('Provincia')" />
                                    <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province', $stablishmentDetails->province)" />
                                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="country" :value="__('País')" />
                                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $stablishmentDetails->country ?? 'España')" />
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium mb-4">Contacto</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="phone" :value="__('Teléfono')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $stablishmentDetails->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $stablishmentDetails->email)" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                Guardar
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
