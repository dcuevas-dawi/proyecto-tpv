<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Mesa {{ $table->number }}</h1>

            @if ($order && $order->status == 'abierto')
                <div class="border-b border-gray-300 pb-6 mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700">Pedido Abierto</h3>

                    <form action="{{ route('orders.addProduct', $table->id) }}" method="POST" class="mt-4 space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label for="product_id" class="block text-lg text-gray-600">Producto</label>
                            <select name="product_id" id="product_id" class="form-select block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <!-- Drinks -->
                                    <optgroup label="Bebidas">
                                        @foreach ($products as $product)
                                            @if ($product->category == 'drink')
                                                <option value="{{ $product->id }}">{{ $product->name }} - €{{ $product->price }}</option>
                                            @endif
                                        @endforeach
                                <!-- Food -->
                                    <optgroup label="Comidas">
                                        @foreach ($products as $product)
                                            @if ($product->category == 'food')
                                                <option value="{{ $product->id }}">{{ $product->name }} - €{{ $product->price }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                <!-- Other -->
                                    <optgroup label="Otros">
                                        @foreach ($products as $product)
                                            @if ($product->category == 'other')
                                                <option value="{{ $product->id }}">{{ $product->name }} - €{{ $product->price }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="quantity" class="block text-lg text-gray-600">Cantidad</label>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="decrementAddQuantity()" class="p-3 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 w-12 h-12 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <input type="number" name="quantity" id="quantity" class="form-input block w-20 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none text-center text-xl" value="1" required min="1" readonly>

                                <button type="button" onclick="incrementAddQuantity()" class="p-3 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 w-12 h-12 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Añadir al Pedido</button>
                    </form>
                </div>

                <div class="border-b border-gray-300 pb-6 mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700">Productos en el Pedido</h3>
                    <ul class="space-y-4 mt-4">
                        @foreach ($order->products as $product)
                            <li class="flex justify-between items-center text-gray-600 p-2 bg-gray-100 rounded">
                                <span>{{ $product->name }}</span>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('orders.updateQuantity', ['orderId' => $order->id, 'productId' => $product->id]) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button" onclick="decrementQuantity(this)" class="p-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 w-10 h-10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="1"
                                               class="w-16 p-1 border border-gray-300 rounded text-center" readonly>

                                        <button type="button" onclick="incrementQuantity(this)" class="p-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 w-10 h-10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <button type="submit" class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 w-10 h-10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                    </form>

                                    <span class="mx-2">Precio Total: €{{ $product->pivot->quantity * $product->pivot->price_at_time }}</span>

                                    <form action="{{ route('orders.removeProduct', ['orderId' => $order->id, 'productId' => $product->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 w-10 h-10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                        <li class="flex justify-between justify-self-end font-bold text-gray-600">
                            Total: {{ $order->products->sum(function($product) {
                                return $product->pivot->quantity * $product->pivot->price_at_time;
                            }) }}€
                        </li>
                    </ul>
                </div>

                <form action="{{ route('tables.closeOrder', $table->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-green-500 text-white font-semibold rounded-md shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Cerrar y Marcar como Pagado</button>
                </form>
            @else
                <p class="text-lg text-gray-600 mb-6">No hay un pedido abierto para esta mesa.</p>

                <form action="{{ route('orders.create', $table->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-md shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">Crear Nuevo Pedido</button>
                </form>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/tables.js') }}"></script>
    @endpush

</x-app-layout>
