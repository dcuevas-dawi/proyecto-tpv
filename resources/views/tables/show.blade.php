<x-app-layout>
    <div class="container">
        <h1 class="text-3xl font-semibold text-gray-800 mb-3">Mesa {{ $table->number }}</h1>
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-2" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-2" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div class="flex gap-4">
            <!-- Panel izquierdo: Productos organizados por categorías -->
            @if ($order && $order->status == 'abierto')
                <div class="w-2/3 h-[85vh] overflow-y-auto pr-2">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-3 sticky top-0 bg-white pt-2 pb-2">Añadir Productos</h2>

                        <!-- Bebidas -->
                        <div class="mb-6">
                            <h3 class="text-xl font-medium text-gray-700 mb-2 border-b pb-2 sticky top-14 bg-white">Bebidas</h3>
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($products as $product)
                                    @if ($product->category == 'drink')
                                        <form action="{{ route('orders.addProduct', $table->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="table-card group relative w-full bg-green-300 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out h-28">
                                                <div class="table-card-body p-3 flex flex-col justify-evenly h-full">
                                                    <h4 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h4>
                                                    <p class="text-gray-700 font-medium">€{{ $product->price }}</p>
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Comidas -->
                        <div class="mb-6">
                            <h3 class="text-xl font-medium text-gray-700 mb-2 border-b pb-2 sticky top-14 bg-white">Comidas</h3>
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($products as $product)
                                    @if ($product->category == 'food')
                                        <form action="{{ route('orders.addProduct', $table->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="table-card group relative w-full bg-green-300 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out h-28">
                                                <div class="table-card-body p-3 flex flex-col justify-evenly h-full">
                                                    <h4 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h4>
                                                    <p class="text-gray-700 font-medium">€{{ $product->price }}</p>
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Otros -->
                        <div>
                            <h3 class="text-xl font-medium text-gray-700 mb-2 border-b pb-2 sticky top-14 bg-white">Otros</h3>
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($products as $product)
                                    @if ($product->category == 'other')
                                        <form action="{{ route('orders.addProduct', $table->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="table-card group relative w-full bg-green-300 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out h-28">
                                                <div class="table-card-body p-3 flex flex-col justify-evenly h-full">
                                                    <h4 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h4>
                                                    <p class="text-gray-700 font-medium">€{{ $product->price }}</p>
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Panel derecho: Lista de pedido -->
            <div class="w-1/3 bg-white p-4 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Pedido Actual</h2>

                @if ($order && $order->status == 'abierto')
                    <div class="space-y-4 mb-4">
                        <ul class="space-y-2 max-h-[68vh] overflow-y-auto">
                            @foreach ($order->products as $product)
                                <li class="bg-gray-100 rounded-lg p-3 shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-lg">{{ $product->name }}</span>
                                        <span class="font-bold">€{{ $product->pivot->price_at_time }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <form action="{{ route('orders.updateQuantity', ['orderId' => $order->id, 'productId' => $product->id]) }}" method="POST" class="flex items-center space-x-2 quantity-form">
                                            @csrf
                                            @method('PATCH')

                                            <button type="button" onclick="updateQuantity(this, 'decrease')" class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-xl font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="1"
                                                   class="h-10 w-16 border border-gray-300 rounded text-center text-lg quantity-input" readonly>

                                            <button type="button" onclick="updateQuantity(this, 'increase')" class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-xl font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>

                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium">Total: €{{ $product->pivot->quantity * $product->pivot->price_at_time }}</span>

                                            <form action="{{ route('orders.removeProduct', ['orderId' => $order->id, 'productId' => $product->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="h-10 w-10 bg-red-500 rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-t-2 border-gray-300 pt-3 mt-3">
                            <p class="text-xl font-bold text-gray-800 flex justify-between">
                                <span>Total:</span>
                                <span>{{ $order->products->sum(function($product) {
                                    return $product->pivot->quantity * $product->pivot->price_at_time;
                                }) }}€</span>
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('tables.closeOrder', $table->id) }}" method="POST" id="closeOrderForm" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg shadow transition duration-300">
                            Cerrar y Marcar como Pagado
                        </button>
                    </form>
                @else
                    <div class="bg-gray-100 p-6 rounded-lg text-center">
                        <p class="text-lg text-gray-600 mb-6">No hay un pedido abierto para esta mesa.</p>

                        <form action="{{ route('orders.create', $table->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-primary hover:bg-primaryLight text-white text-lg font-semibold rounded-lg shadow transition duration-300">
                                Crear Nuevo Pedido
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/tables.js') }}"></script>
    @endpush
</x-app-layout>
