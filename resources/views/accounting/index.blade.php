<!-- View for accounting -->

<x-app-layout>
    <div class="w-full h-auto py-6 bg-gray-100">
        <div class="flex items-center justify-center mb-6">
            <h2 class="text-4xl font-bold text-center mb-8">Contabilidad</h2>
        </div>

        <div class="">
            <form id="accountingForm" action="{{ route('accounting.report') }}" method="GET" class="p-6">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700">Periodo</label>
                        <select id="period" name="period"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
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
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                            Consultar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-2 gap-6">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Total ventas</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($data['total_sales'], 2) }} €</p>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Total tickets</h3>
                <p class="text-3xl font-bold text-green-600">{{ $data['total_orders'] }}</p>
            </div>
        </div>

        <!-- Data table -->
        @if(count($data['data']) > 0)
            <div class="overflow-x-auto rounded-lg shadow-lg">
                <table class="w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="py-3 px-6 text-left font-medium">Periodo</th>
                        <th class="py-3 px-6 text-right font-medium">Tickets</th>
                        <th class="py-3 px-6 text-right font-medium">Total ventas</th>
                        <th class="py-3 px-6 text-right font-medium">Media por ticket</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600">
                    @foreach($data['data'] as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
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
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <p class="text-lg text-gray-600">No hay datos disponibles para el periodo seleccionado.</p>
            </div>
        @endif
    </div>
    @push('scripts')
        <script src="{{ asset('js/accounting.js') }}"></script>
    @endpush
</x-app-layout>
