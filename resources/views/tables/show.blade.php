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
                            <input type="number" name="quantity" id="quantity" class="form-input block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="1" required min="1">
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Añadir al Pedido</button>
                    </form>
                </div>

                <div class="border-b border-gray-300 pb-6 mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700">Productos en el Pedido</h3>
                    <ul class="space-y-4 mt-4">
                        @foreach ($order->products as $product)
                            <li class="flex justify-between text-gray-600">
                                <span>{{ $product->name }}</span>
                                <span>Cantidad: {{ $product->pivot->quantity }} - Precio Total: €{{ $product->pivot->quantity * $product->pivot->price_at_time }}</span>
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
</x-app-layout>
