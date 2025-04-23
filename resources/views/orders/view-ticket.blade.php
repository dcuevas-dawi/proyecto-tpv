<!-- View for viewing ticket details -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Ticket #') . $order->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Información del pedido -->
                <div class="mb-6">
                    <p class="text-lg"><strong>Ticket #:</strong> {{ $order->id }}</p>
                    <p><strong>Mesa:</strong> {{ $order->table->number }}</p>
                    <p><strong>Fecha apertura:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha cierre:</strong> {{ \Carbon\Carbon::parse($order->closed_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Atendido por:</strong> {{ $order->employee->name ?? 'No especificado' }}</p>
                </div>

                <!-- Productos -->
                <table class="w-full mb-6">
                    <thead>
                    <tr class="border-b-2 border-gray-200 text-left">
                        <th class="py-2">Descripción</th>
                        <th class="py-2 text-right">Cant.</th>
                        <th class="py-2 text-right">Precio</th>
                        <th class="py-2 text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->products as $product)
                        <tr class="border-b border-gray-100">
                            <td class="py-2">{{ $product->name }}</td>
                            <td class="py-2 text-right">{{ $product->pivot->quantity }}</td>
                            <td class="py-2 text-right">{{ number_format($product->pivot->price_at_time, 2) }} €</td>
                            <td class="py-2 text-right">{{ number_format($product->pivot->quantity * $product->pivot->price_at_time, 2) }} €</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Total -->
                <div class="text-right text-xl font-bold border-t-2 border-gray-300 pt-4">
                    <p>TOTAL: {{ number_format($order->total_price, 2) }} €</p>
                </div>

                <!-- Botón para volver y para imprimir -->
                <div class="text-center mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('orders.history') }}"
                       class="block py-4 bg-green-600 hover:bg-green-700 text-white text-xl rounded-lg shadow text-center w-full sm:w-1/2">
                        <i class="fas fa-arrow-left mr-2"></i> Volver al Historial
                    </a>
                    <a href="{{ route('orders.print', $order->id) }}" target="_blank"
                       class="block py-4 bg-green-600 hover:bg-green-700 text-white text-xl rounded-lg shadow text-center w-full sm:w-1/2">
                        <i class="fas fa-print mr-2"></i> Imprimir Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
