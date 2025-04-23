<!-- View for recovering the history of cash registers -->

<x-app-layout>
    <div class="py-2">
        <div class="max-w-full mx-auto px-3 lg:px-4">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-3">
                Historial de Caja
            </h1>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-2" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-2" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Filter by dates -->
                <div class="bg-gray-50 p-3 border-b border-gray-200">
                    <form action="{{ route('cash-register.history') }}" method="GET" class="flex flex-row gap-2 items-end">
                        <div class="flex-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicial:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-7 days'))) }}"
                                   class="w-full p-1 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha final:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}"
                                   class="w-full p-1 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-1 px-3 rounded-md transition duration-300">
                                <i class="fas fa-search mr-1"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Historical table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha apertura</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora apertura</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha cierre</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora cierre</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Caja inicial</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Caja final</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Diferencia</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($cashRegisters ?? [] as $register)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-3 py-2 whitespace-nowrap">{{ date('d/m/Y', strtotime($register->opened_at)) }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ date('H:i', strtotime($register->opened_at)) }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $register->closed_at ? date('d/m/Y', strtotime($register->closed_at)) : '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $register->closed_at ? date('H:i', strtotime($register->closed_at)) : '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $register->openingEmployee ? $register->openingEmployee->name : 'Sin empleado' }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ number_format($register->opening_amount, 2) }} €</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">{{ $register->real_closing_amount ? number_format($register->real_closing_amount, 2) . ' €' : '-' }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap {{ $register->difference && $register->difference < 0 ? 'text-red-600 font-medium' : 'text-green-600 font-medium' }}">
                                    {{ $register->difference ? number_format($register->difference, 2) . ' €' : '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($register->closed_at)
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cerrada</span>
                                    @else
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Abierta</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-4 text-center text-gray-500">
                                    No hay registros de caja para mostrar
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination, if exists -->
                @if(isset($cashRegisters) && $cashRegisters->hasPages())
                    <div class="px-3 py-2 bg-white border-t border-gray-200">
                        {{ $cashRegisters->withQueryString()->links() }}
                    </div>
                @endif

                <!-- Navigation buttons -->
                <div class="flex justify-center space-x-3 p-2 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('cash-register.open') }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-300">
                        <i class="fas fa-cash-register mr-1"></i> Abrir Caja
                    </a>
                    <a href="{{ route('cash-register.close') }}" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-300">
                        <i class="fas fa-door-closed mr-1"></i> Cerrar Caja
                    </a>
                    <a href="{{ route('menu') }}" class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-300">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al Menú
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
