<x-app-layout>
    <div class="py-2">
        <div class="max-w-full mx-auto px-2 sm:px-3 lg:px-4">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-3">
                Cierre de Caja
            </h1>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-2" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="p-4 bg-gray-50 border-b">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Fecha de apertura:</p>
                            <p class="text-lg font-medium">{{ date('d/m/Y H:i', strtotime($cashRegister->opened_at)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Importe inicial:</p>
                            <p class="text-lg font-medium">{{ number_format($cashRegister->opening_amount, 2) }} €</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('cash-register.close') }}" method="POST" class="p-4">
                    @csrf

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label for="real_closing_amount" class="block text-gray-700 text-lg font-bold">
                                Importe de cierre (€)
                            </label>

                            <div class="text-right">
                                <p class="text-sm text-gray-600">Ventas del día:</p>
                                <p class="text-lg font-medium">{{ number_format($totalSales, 2) }} €</p>
                            </div>
                        </div>

                        <input type="number"
                               name="real_closing_amount"
                               id="real_closing_amount"
                               step="0.01"
                               min="0"
                               value=""
                               class="w-full p-3 text-xl border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                        @error('real_closing_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-sm text-gray-600">
                            Importe esperado: <span class="font-bold">{{ number_format($theoreticalClosingAmount, 2) }} €</span>
                            (Inicial + Ventas)
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="comments" class="block text-gray-700 text-lg font-bold mb-2">
                            Notas (opcional)
                        </label>
                        <textarea
                            name="comments"
                            id="comments"
                            rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Añade comentarios sobre el cierre (faltante, sobrante, incidencias...)"></textarea>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('menu') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg text-lg">
                            Cerrar Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
