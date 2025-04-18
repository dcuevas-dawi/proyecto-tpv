// Gestión de categorías de productos
function initCategorySelection() {
    const categoryOptions = document.querySelectorAll('.category-option');
    if (!categoryOptions.length) return;

    // Inicializar el estado
    categoryOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio.checked) {
            highlightSelected(option);
        }
    });

    // Manejar selección
    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Quitar selección de todos
            categoryOptions.forEach(opt => {
                opt.classList.remove('border-yellow-500', 'border-red-500', 'border-purple-500', 'bg-yellow-100', 'bg-red-100', 'bg-purple-100');
                opt.classList.add('border-yellow-300', 'border-red-300', 'border-purple-300');
            });

            // Resaltar el seleccionado
            highlightSelected(this);
        });
    });
}

function highlightSelected(option) {
    const radio = option.querySelector('input[type="radio"]');
    option.classList.remove('border-yellow-300', 'border-red-300', 'border-purple-300');

    if (radio.value === 'food') {
        option.classList.add('border-yellow-500', 'bg-yellow-100');
    } else if (radio.value === 'drink') {
        option.classList.add('border-red-500', 'bg-red-100');
    } else {
        option.classList.add('border-purple-500', 'bg-purple-100');
    }
}

// Confirmaciones para eliminar y restaurar productos
function initConfirmActions() {
    // Función para confirmar eliminación
    window.confirmDelete = function(button) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            button.closest('form').submit();
        }
    };

    // Función para confirmar restauración
    window.confirmRestore = function(button) {
        if (confirm('¿Estás seguro de que deseas restaurar este producto?')) {
            button.closest('form').submit();
        }
    };
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    initCategorySelection();
    initConfirmActions();
});
