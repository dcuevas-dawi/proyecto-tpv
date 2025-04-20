<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Productos') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                <i class="fas fa-plus mr-2"></i>Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Filtros mejorados por categoría y estado -->
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route('products.index', ['filter' => 'all']) }}"
                       class="bg-green-500 hover:bg-green-600 text-white py-3 px-5 rounded-lg text-lg {{ $filter == 'all' ? 'ring-4 ring-green-300' : '' }}">
                        Todos
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'food']) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-5 rounded-lg text-lg {{ $filter == 'food' ? 'ring-4 ring-yellow-300' : '' }}">
                        Comida
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'drink']) }}"
                       class="bg-red-500 hover:bg-red-600 text-white py-3 px-5 rounded-lg text-lg {{ $filter == 'drink' ? 'ring-4 ring-red-300' : '' }}">
                        Bebida
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'other']) }}"
                       class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-5 rounded-lg text-lg {{ $filter == 'other' ? 'ring-4 ring-purple-300' : '' }}">
                        Otros
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'inactive']) }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white py-3 px-5 rounded-lg text-lg {{ $filter == 'inactive' ? 'ring-4 ring-gray-300' : '' }}">
                        Eliminados
                    </a>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="product-card border rounded-lg shadow-md overflow-hidden {{ !$product->active ? 'opacity-75' : '' }}">
                                <div class="p-5 border-b {{ $product->category == 'food' ? 'bg-yellow-100' : ($product->category == 'drink' ? 'bg-red-100' : 'bg-purple-100') }}">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-xl font-bold mb-2">{{ $product->name }}</h3>
                                        @if(!$product->active)
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Inactivo</span>
                                        @endif
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-white mb-2
                                        {{ $product->category == 'food' ? 'bg-yellow-500' : ($product->category == 'drink' ? 'bg-red-500' : 'bg-purple-500') }}">
                                        {{ $product->category == 'food' ? 'Comida' : ($product->category == 'drink' ? 'Bebida' : 'Otros') }}
                                    </span>
                                    <p class="text-xl font-bold text-green-600">{{ number_format($product->price, 2) }} €</p>
                                </div>
                                <div class="p-5 bg-white">
                                    <p class="text-gray-700 mb-4">{{ $product->description ?: 'Sin descripción' }}</p>
                                    <div class="flex justify-between">
                                        <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded-lg text-lg">
                                            <i class="fas fa-edit mr-2"></i>Editar
                                        </a>

                                        @if($product->active)
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-5 rounded-lg text-lg">
                                                    <i class="fas fa-trash mr-2"></i>Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('products.restore', $product) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="button" onclick="confirmRestore(this)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-5 rounded-lg text-lg">
                                                    <i class="fas fa-undo mr-2"></i>Restaurar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-8 rounded-lg text-center">
                        <p class="text-lg text-gray-600">No hay productos disponibles con el filtro seleccionado.</p>
                        <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Crear un nuevo producto
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
