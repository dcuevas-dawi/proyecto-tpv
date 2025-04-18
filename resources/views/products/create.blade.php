<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form id="product-form" method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('name') border-red-500 @enderror"
                               placeholder="Nombre del producto">
                        <p id="name-error" class="mt-1 text-sm text-red-600 hidden">Error en el nombre</p>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-gray-700">Precio (€) *</label>
                        <input type="text" name="price" id="price" value="{{ old('price') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('price') border-red-500 @enderror"
                               placeholder="0.00">
                        <p id="price-error" class="mt-1 text-sm text-red-600 hidden">Error en el precio</p>
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <span class="block text-sm font-medium text-gray-700 mb-2">Categoría *</span>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="category-option border-2 border-yellow-300 rounded-lg p-4 cursor-pointer">
                                <input type="radio" name="category" id="food" value="food" class="hidden" {{ old('category') == 'food' ? 'checked' : '' }}>
                                <label for="food" class="flex items-center justify-center cursor-pointer">
                                    <span class="text-lg font-medium">Comida</span>
                                </label>
                            </div>

                            <div class="category-option border-2 border-red-300 rounded-lg p-4 cursor-pointer">
                                <input type="radio" name="category" id="drink" value="drink" class="hidden" {{ old('category') == 'drink' ? 'checked' : '' }}>
                                <label for="drink" class="flex items-center justify-center cursor-pointer">
                                    <span class="text-lg font-medium">Bebida</span>
                                </label>
                            </div>

                            <div class="category-option border-2 border-purple-300 rounded-lg p-4 cursor-pointer">
                                <input type="radio" name="category" id="other" value="other" class="hidden" {{ old('category') == 'other' ? 'checked' : '' }}>
                                <label for="other" class="flex items-center justify-center cursor-pointer">
                                    <span class="text-lg font-medium">Otros</span>
                                </label>
                            </div>
                        </div>
                        <p id="category-error" class="mt-1 text-sm text-red-600 hidden">Error en la categoría</p>
                        @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('description') border-red-500 @enderror"
                                  placeholder="Descripción del producto">{{ old('description') }}</textarea>
                        <p id="description-error" class="mt-1 text-sm text-red-600 hidden">Error en la descripción</p>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/product.js') }}"></script>
    @endpush
</x-app-layout>
