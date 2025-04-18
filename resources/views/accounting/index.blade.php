<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contabilidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="accountingForm" action="{{ route('accounting.report') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700">Periodo</label>
                                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-lg py-2">
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Diario</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                    <option value="quarterly" {{ $period == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Anual</option>
                                </select>
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-lg py-2">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-lg py-2">
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Consultar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Resumen -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-xl font-semibold mb-4">Resumen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <p class="text-gray-500 text-sm uppercase">Total ventas</p>
                                <p class="text-3xl font-bold text-blue-600">{{ number_format($data['total_sales'], 2) }} €</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <p class="text-gray-500 text-sm uppercase">Total tickets</p>
                                <p class="text-3xl font-bold text-green-600">{{ $data['total_orders'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de datos -->
                    @if(count($data['data']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Periodo</th>
                                    <th class="py-3 px-6 text-right">Tickets</th>
                                    <th class="py-3 px-6 text-right">Total ventas</th>
                                    <th class="py-3 px-6 text-right">Media por ticket</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-base">
                                @foreach($data['data'] as $row)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left font-medium">{{ $row['period'] }}</td>
                                        <td class="py-3 px-6 text-right">{{ $row['count'] }}</td>
                                        <td class="py-3 px-6 text-right font-medium">{{ number_format($row['sales'], 2) }} €</td>
                                        <td class="py-3 px-6 text-right">
                                            {{ number_format($row['sales'] / $row['count'], 2) }} €
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr class="bg-gray-50 font-bold">
                                    <td class="py-3 px-6 text-left">TOTAL</td>
                                    <td class="py-3 px-6 text-right">{{ $data['total_orders'] }}</td>
                                    <td class="py-3 px-6 text-right">{{ number_format($data['total_sales'], 2) }} €</td>
                                    <td class="py-3 px-6 text-right">
                                        {{ $data['total_orders'] > 0 ? number_format($data['total_sales'] / $data['total_orders'], 2) : '0.00' }} €
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 rounded-lg text-center">
                            <p class="text-lg text-gray-600">No hay datos disponibles para el periodo seleccionado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/accounting.js') }}"></script>
    @endpush
</x-app-layout>
