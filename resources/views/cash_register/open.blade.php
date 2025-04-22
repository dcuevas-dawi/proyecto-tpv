<x-app-layout>
    <div class="py-2">
        <div class="max-w-full mx-auto px-2 sm:px-3 lg:px-4">
            <div class="bg-white shadow-md rounded-lg overflow-hidden mx-auto max-w-lg">
                <div class="bg-primary py-3">
                    <h1 class="text-2xl font-bold text-center text-white">
                        Apertura de Caja
                    </h1>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 m-2" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('cash-register.open') }}" method="POST" class="p-4">
                    @csrf

                    <div class="mb-4">
                        <label for="display" class="block text-gray-700 text-lg font-medium mb-2 text-center">
                            Importe inicial de caja (€)
                        </label>
                        <input type="text"
                               id="display"
                               readonly
                               value="0.00"
                               class="w-full p-3 text-2xl text-center border border-gray-300 rounded-lg bg-gray-50 mb-3"
                        >
                        <input type="hidden" name="opening_amount" id="opening_amount" value="0.00">
                        @error('opening_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teclado numérico estilo login -->
                    <div class="grid grid-cols-3 gap-2 mb-6 max-w-xs mx-auto">
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="7">7</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="8">8</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="9">9</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="4">4</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="5">5</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="6">6</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="1">1</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="2">2</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="3">3</button>
                        <button type="button" id="clear-btn" class="bg-red-100 hover:bg-red-200 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center">C</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key="0">0</button>
                        <button type="button" class="num-key bg-blue-50 hover:bg-blue-100 py-4 rounded text-xl font-medium aspect-square flex items-center justify-center" data-key=".">.</button>
                    </div>

                    <div class="flex flex-col gap-2 mt-4">
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded text-lg w-full">
                            Abrir Caja
                        </button>
                        <a href="{{ route('menu') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded text-lg text-center w-full">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const display = document.getElementById('display');
            const hiddenInput = document.getElementById('opening_amount');
            const numKeys = document.querySelectorAll('.num-key');
            const clearBtn = document.getElementById('clear-btn');

            let currentValue = '0';
            let hasDecimal = false;
            let decimalDigits = 0;

            // Actualizar la pantalla y el campo oculto
            function updateDisplay() {
                // Formatear para mostrar siempre 2 decimales
                const numValue = parseFloat(currentValue);
                display.value = numValue.toFixed(2);
                hiddenInput.value = numValue.toFixed(2);
            }

            // Manejar clics en botones numéricos
            numKeys.forEach(key => {
                key.addEventListener('click', function() {
                    const digit = this.dataset.key;

                    if (digit === '.') {
                        if (!hasDecimal) {
                            hasDecimal = true;
                            if (currentValue === '0') {
                                currentValue = '0.';
                            } else {
                                currentValue += '.';
                            }
                        }
                    } else {
                        if (currentValue === '0' && digit !== '0') {
                            currentValue = digit;
                        } else if (hasDecimal) {
                            if (decimalDigits < 2) {
                                currentValue += digit;
                                decimalDigits++;
                            }
                        } else {
                            currentValue += digit;
                        }
                    }

                    updateDisplay();
                });
            });

            // Limpiar el valor
            clearBtn.addEventListener('click', function() {
                currentValue = '0';
                hasDecimal = false;
                decimalDigits = 0;
                updateDisplay();
            });

            // Inicializar la pantalla
            updateDisplay();
        });
    </script>
</x-app-layout>
