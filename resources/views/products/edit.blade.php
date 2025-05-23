<!-- View for update an existing product -->

<x-app-layout>
    <div class="w-full h-auto py-6 bg-gray-100">
        <div class="flex items-center justify-center mb-4">
            <h2 class="text-4xl font-bold text-center mb-8">Editar Producto</h2>
        </div>

        <div class="w-full mx-auto">
            <div class="flex justify-between mb-3">
                <a href="{{ route('products.index') }}" class="flex items-center justify-center py-2 px-4 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-center shadow transition duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-3">
                <form id="product-form" action="{{ route('products.update', $product->product_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
                                   required>
                            @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <span id="name-error" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio (€)</label>
                            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $product->price) }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
                                   required>
                            @error('price')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <span id="price-error" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="category" id="category"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
                                    required>
                                <option value="">Selecciona una categoría</option>
                                <option value="food" {{ old('category', $product->category) == 'food' ? 'selected' : '' }}>Comida</option>
                                <option value="drink" {{ old('category', $product->category) == 'drink' ? 'selected' : '' }}>Bebida</option>
                                <option value="other" {{ old('category', $product->category) == 'other' ? 'selected' : '' }}>Otros</option>
                            </select>
                            @error('category')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <span id="category-error" class="text-red-500 text-xs hidden"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        <span id="description-error" class="text-red-500 text-xs hidden"></span>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="flex items-center justify-center py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200">
                            <i class="fas fa-save mr-1"></i> Actualizar Producto
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
