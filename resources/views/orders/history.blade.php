<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('orders.byDate') }}" method="GET" class="mb-6">
                        <div class="flex items-end gap-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha inicial')" />
                                <x-text-input id="start_date" type="date" name="start_date" class="mt-1"
                                              value="{{ $start_date ?? now()->format('Y-m-d') }}" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Fecha final')" />
                                <x-text-input id="end_date" type="date" name="end_date" class="mt-1"
                                              value="{{ $end_date ?? now()->format('Y-m-d') }}" />
                            </div>
                            <div>
                                <x-primary-button>Buscar</x-primary-button>
                            </div>
                        </div>
                    </form>

                    @if(isset($orders) && $orders->count() > 0)
                        <div class="mb-4 text-gray-600">
                            @if(isset($start_date) && isset($end_date) && $start_date == $end_date)
                                <p>Mostrando tickets del día {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}</p>
                            @elseif(isset($start_date) && isset($end_date))
                                <p>Mostrando tickets desde {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                                    hasta {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
                            @else
                                <p>Mostrando tickets de hoy</p>
                            @endif
                            <p>Tickets: {{ $orders->count() }}</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Ticket #</th>
                                    <th class="py-3 px-6 text-left">Mesa</th>
                                    <th class="py-3 px-6 text-left">Apertura</th>
                                    <th class="py-3 px-6 text-left">Cierre</th>
                                    <th class="py-3 px-6 text-left">Total</th>
                                    <th class="py-3 px-6 text-left">Empleado</th>
                                    <th class="py-3 px-6 text-center w-36">Acciones</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                @foreach($orders as $order)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left">{{ $order->id }}</td>
                                        <td class="py-3 px-6 text-left">{{ $order->table->number }}</td>
                                        <td class="py-3 px-6 text-left">{{ ($order->created_at) }}</td>
                                        <td class="py-3 px-6 text-left">{{ ($order->closed_at) }}</td>
                                        <td class="py-3 px-6 text-left">
                                            {{ number_format($order->total_price, 2) }} €
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            @if($order->employee_id)
                                                {{ $order->employee->name ?? 'No disponible' }}
                                            @else
                                                No especificado
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex flex-col-2 gap-2 items-center">
                                                <a href="{{ route('orders.view', $order->id) }}"
                                                   class="block w-full py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded-lg text-center shadow">
                                                    <i class="fas fa-eye mr-1"></i> Ver
                                                </a>
                                                <a href="{{ route('orders.print', $order->id) }}"
                                                   class="block w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-center shadow" target="_blank">
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
                        <div class="bg-gray-50 p-4 rounded text-center">
                            No hay tickets para esta fecha.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
