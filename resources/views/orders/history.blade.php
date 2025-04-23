<!-- View to recover the history of tickets -->

<x-app-layout>
    <div class="w-full h-auto p-6 bg-gray-100">
        <div class="flex items-center justify-center mb-6">
            <h1 class="text-4xl font-bold text-gray-800">Historial de Tickets</h1>
        </div>

        <div class="mb-6">
            <form action="{{ route('orders.byDate') }}" method="GET">
                <div class="flex items-end justify-between gap-4">
                    <div class="flex items-end gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha inicial</label>
                            <input id="start_date" type="date" name="start_date"
                                   class="mt-1 block border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                                   value="{{ $start_date ?? now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha final</label>
                            <input id="end_date" type="date" name="end_date"
                                   class="mt-1 block border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                                   value="{{ $end_date ?? now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                                Buscar
                            </button>
                        </div>
                    </div>

                    @if(isset($orders) && $orders->count() > 0)
                        <div>
                            <a href="{{ route('orders.print', $orders->first()->id) }}" target="_blank"
                               class="inline-block bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-print mr-1"></i> Imprimir último ticket
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        @if(isset($orders) && $orders->count() > 0)
            <div class="mb-4 text-gray-600 flex flex-col">
                @if(isset($start_date) && isset($end_date) && $start_date == $end_date)
                    <p>Mostrando tickets del día {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}</p>
                @elseif(isset($start_date) && isset($end_date))
                    <p>Mostrando tickets desde {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                        hasta {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
                @else
                    <p>Mostrando tickets de hoy</p>
                @endif
                <p class="font-medium">Total tickets: {{ $orders->count() }}</p>
            </div>

            <div class="overflow-x-auto rounded-lg shadow-lg">
                <table class="w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="py-3 px-6 text-left font-medium">Ticket #</th>
                        <th class="py-3 px-6 text-left font-medium">Mesa</th>
                        <th class="py-3 px-6 text-left font-medium">Apertura</th>
                        <th class="py-3 px-6 text-left font-medium">Cierre</th>
                        <th class="py-3 px-6 text-left font-medium">Total</th>
                        <th class="py-3 px-6 text-left font-medium">Empleado</th>
                        <th class="py-3 px-6 text-center font-medium w-36">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600">
                    @foreach($orders as $order)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                            <td class="py-3 px-6 text-left">{{ $order->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $order->table->number }}</td>
                            <td class="py-3 px-6 text-left">{{ ($order->created_at) }}</td>
                            <td class="py-3 px-6 text-left">{{ ($order->closed_at) }}</td>
                            <td class="py-3 px-6 text-left font-medium">
                                {{ number_format($order->total_price, 2) }} €
                            </td>
                            <td class="py-3 px-6 text-left">
                                @if($order->employee_id)
                                    {{ $order->employee->name ?? 'No disponible' }}
                                @else
                                    No especificado
                                @endif
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex flex-row gap-2 justify-center">
                                    <a href="{{ route('orders.view', $order->id) }}"
                                       class="flex items-center justify-center min-w-[90px] py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </a>
                                    <a href="{{ route('orders.print', $order->id) }}"
                                       class="flex items-center justify-center min-w-[110px] py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded-md text-center shadow transition duration-200" target="_blank">
                                        <i class="fas fa-print mr-1"></i> Imprimir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <p class="text-lg text-gray-600">No hay tickets para este período.</p>
            </div>
        @endif
    </div>
</x-app-layout>
