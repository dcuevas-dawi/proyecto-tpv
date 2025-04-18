<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Nombre del producto</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 py-3 text-lg"
                               placeholder="Nombre del producto">
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-lg font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 py-3 text-lg"
                                  placeholder="Descripción del producto">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-lg font-medium text-gray-700 mb-2">Precio (€)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 py-3 text-lg"
                               placeholder="0.00">
                        @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <span class="block text-lg font-medium text-gray-700 mb-3">Categoría</span>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="category-option border-2 border-yellow-300 rounded-lg p-4 text-center cursor-pointer hover:bg-yellow-50">
                                <input type="radio" name="category" value="food" class="sr-only" {{ old('category', $product->category) == 'food' ? 'checked' : '' }}>
                                <span class="text-3xl mb-2 block">🍔</span>
                                <span class="text-lg font-medium">Comida</span>
                            </label>

                            <label class="category-option border-2 border-red-300 rounded-lg p-4 text-center cursor-pointer hover:bg-red-50">
                                <input type="radio" name="category" value="drink" class="sr-only" {{ old('category', $product->category) == 'drink' ? 'checked' : '' }}>
                                <span class="text-3xl mb-2 block">🥤</span>
                                <span class="text-lg font-medium">Bebida</span>
                            </label>

                            <label class="category-option border-2 border-purple-300 rounded-lg p-4 text-center cursor-pointer hover:bg-purple-50">
                                <input type="radio" name="category" value="other" class="sr-only" {{ old('category', $product->category) == 'other' ? 'checked' : '' }}>
                                <span class="text-3xl mb-2 block">🍽️</span>
                                <span class="text-lg font-medium">Otros</span>
                            </label>
                        </div>
                        @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Actualizar producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        @push('scripts')
            <script src="{{ asset('js/product.js') }}"></script>
        @endpush
    @endpush
</x-app-layout>
