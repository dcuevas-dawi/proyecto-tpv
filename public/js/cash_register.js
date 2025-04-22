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
