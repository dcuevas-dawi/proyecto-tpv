<x-app-layout>
    <div class="w-full h-auto py-6 bg-gray-100">
        <div class="flex items-center justify-center mb-4">
            <h2 class="text-4xl font-bold text-center mb-8">Datos del Establecimiento</h2>
        </div>

        <div class="w-full mx-auto">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-3">
                <div class="p-4 text-gray-900">
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
                                <x-input-label for="commercial_name" :value="__('Nombre Comercial')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-text-input id="commercial_name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="commercial_name" :value="old('commercial_name', $stablishmentDetails->commercial_name)" />
                                <x-input-error :messages="$errors->get('commercial_name')" class="text-red-500 text-xs" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="legal_name" :value="__('Nombre Legal')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="legal_name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="legal_name" :value="old('legal_name', $stablishmentDetails->legal_name)" />
                                    <x-input-error :messages="$errors->get('legal_name')" class="text-red-500 text-xs" />
                                </div>

                                <div>
                                    <x-input-label for="cif" :value="__('CIF/NIF')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="cif" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="cif" :value="old('cif', $stablishmentDetails->cif)" />
                                    <x-input-error :messages="$errors->get('cif')" class="text-red-500 text-xs" />
                                </div>
                            </div>
                        </div>

                        <!-- Adress -->
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium mb-4">Dirección</h3>

                            <div class="mb-4">
                                <x-input-label for="address" :value="__('Dirección')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-text-input id="address" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="address" :value="old('address', $stablishmentDetails->address)" />
                                <x-input-error :messages="$errors->get('address')" class="text-red-500 text-xs" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="postal_code" :value="__('Código Postal')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="postal_code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="postal_code" :value="old('postal_code', $stablishmentDetails->postal_code)" />
                                    <x-input-error :messages="$errors->get('postal_code')" class="text-red-500 text-xs" />
                                </div>

                                <div>
                                    <x-input-label for="city" :value="__('Ciudad')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="city" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="city" :value="old('city', $stablishmentDetails->city)" />
                                    <x-input-error :messages="$errors->get('city')" class="text-red-500 text-xs" />
                                </div>

                                <div>
                                    <x-input-label for="province" :value="__('Provincia')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="province" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="province" :value="old('province', $stablishmentDetails->province)" />
                                    <x-input-error :messages="$errors->get('province')" class="text-red-500 text-xs" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="country" :value="__('País')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-text-input id="country" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="country" :value="old('country', $stablishmentDetails->country ?? 'España')" />
                                <x-input-error :messages="$errors->get('country')" class="text-red-500 text-xs" />
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-medium mb-4">Contacto</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="phone" :value="__('Teléfono')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="phone" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="text" name="phone" :value="old('phone', $stablishmentDetails->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="text-red-500 text-xs" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                                    <x-text-input id="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" type="email" name="email" :value="old('email', $stablishmentDetails->email)" />
                                    <x-input-error :messages="$errors->get('email')" class="text-red-500 text-xs" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
