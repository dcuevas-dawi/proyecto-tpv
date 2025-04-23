<!-- View for list all products, with filters and actions -->

<x-app-layout>
    <div class="w-full h-auto p-4 bg-gray-100">
        <div class="flex items-center justify-center mb-4">
            <h1 class="text-3xl font-semibold text-gray-800">Gestión de Productos</h1>
        </div>

        <div class="w-full mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex justify-end mb-3">
                <a href="{{ route('products.create') }}" class="flex items-center justify-center py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200">
                    <i class="fas fa-plus mr-1"></i> Nuevo Producto
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-3">
                <div class="mb-3 flex flex-wrap gap-2">
                    <a href="{{ route('products.index', ['filter' => 'all']) }}"
                       class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-center shadow transition duration-200 {{ $filter == 'all' ? 'ring-2 ring-green-300' : '' }}">
                        Todos
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'food']) }}"
                       class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-center shadow transition duration-200 {{ $filter == 'food' ? 'ring-2 ring-green-300' : '' }}">
                        Comida
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'drink']) }}"
                       class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-center shadow transition duration-200 {{ $filter == 'drink' ? 'ring-2 ring-green-300' : '' }}">
                        Bebida
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'other']) }}"
                       class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-center shadow transition duration-200 {{ $filter == 'other' ? 'ring-2 ring-green-300' : '' }}">
                        Otros
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'inactive']) }}"
                       class="flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-md text-center shadow transition duration-200 {{ $filter == 'inactive' ? 'ring-2 ring-gray-300' : '' }}">
                        Eliminados
                    </a>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($products as $product)
                            <div class="product-card border rounded-lg shadow overflow-hidden {{ !$product->active ? 'opacity-75' : '' }}">
                                <div class="p-2 border-b bg-green-50">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                                        @if(!$product->active)
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Inactivo</span>
                                        @endif
                                    </div>
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold text-white mb-1 bg-green-600">
                                        {{ $product->category == 'food' ? 'Comida' : ($product->category == 'drink' ? 'Bebida' : 'Otros') }}
                                    </span>
                                    <p class="text-lg font-bold text-green-600">{{ number_format($product->price, 2) }} €</p>
                                </div>
                                <div class="p-2 bg-white">
                                    <p class="text-gray-700 mb-2 text-xs">{{ $product->description ?: 'Sin descripción' }}</p>
                                    <div class="flex justify-between">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="flex items-center justify-center py-1 px-2 bg-green-600 hover:bg-green-700 text-white rounded text-center shadow transition duration-200 text-xs">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>

                                        @if($product->active)
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)"
                                                        class="flex items-center justify-center py-1 px-2 bg-red-600 hover:bg-red-700 text-white rounded text-center shadow transition duration-200 text-xs">
                                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('products.restore', $product) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="button" onclick="confirmRestore(this)"
                                                        class="flex items-center justify-center py-1 px-2 bg-green-600 hover:bg-green-700 text-white rounded text-center shadow transition duration-200 text-xs">
                                                    <i class="fas fa-undo mr-1"></i> Restaurar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-lg text-gray-600">No hay productos disponibles con el filtro seleccionado.</p>
                        <a href="{{ route('products.create') }}"
                           class="mt-4 inline-block flex items-center justify-center py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200">
                            <i class="fas fa-plus mr-1"></i> Crear un nuevo producto
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/product.js') }}"></script>
    @endpush
</x-app-layout>
