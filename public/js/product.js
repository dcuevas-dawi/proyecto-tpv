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

// Validación de formularios de productos
function initFormValidation() {
    const productForm = document.getElementById('product-form');
    if (!productForm) return;

    productForm.addEventListener('submit', function(e) {
        let isValid = true;
        let firstError = null;

        // Validar nombre
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('name-error');

        if (!nameInput.value.trim()) {
            nameError.textContent = 'El nombre del producto es obligatorio';
            nameError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || nameInput;
        } else if (nameInput.value.length > 100) {
            nameError.textContent = 'El nombre no puede tener más de 100 caracteres';
            nameError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || nameInput;
        } else {
            nameError.classList.add('hidden');
        }

        // Validar precio
        const priceInput = document.getElementById('price');
        const priceError = document.getElementById('price-error');
        const price = parseFloat(priceInput.value.replace(',', '.'));

        if (!priceInput.value.trim()) {
            priceError.textContent = 'El precio es obligatorio';
            priceError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || priceInput;
        } else if (isNaN(price)) {
            priceError.textContent = 'El precio debe ser un número';
            priceError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || priceInput;
        } else if (price < 0.01) {
            priceError.textContent = 'El precio mínimo es 0,01 €';
            priceError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || priceInput;
        } else if (price > 9999.99) {
            priceError.textContent = 'El precio máximo es 9.999,99 €';
            priceError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || priceInput;
        } else {
            priceError.classList.add('hidden');
        }

        // Validar categoría
        const categorySelected = document.querySelector('input[name="category"]:checked');
        const categoryError = document.getElementById('category-error');

        if (!categorySelected) {
            categoryError.textContent = 'Debes seleccionar una categoría';
            categoryError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || document.querySelector('.category-option');
        } else {
            categoryError.classList.add('hidden');
        }

        // Validar descripción (opcional)
        const descriptionInput = document.getElementById('description');
        const descriptionError = document.getElementById('description-error');

        if (descriptionInput.value.length > 500) {
            descriptionError.textContent = 'La descripción no puede tener más de 500 caracteres';
            descriptionError.classList.remove('hidden');
            isValid = false;
            firstError = firstError || descriptionInput;
        } else {
            descriptionError.classList.add('hidden');
        }

        // Si hay errores, prevenir envío y hacer scroll al primer error
        if (!isValid) {
            e.preventDefault();
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Validación en tiempo real
    const inputs = productForm.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const errorElement = document.getElementById(`${this.id}-error`);
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
        });
    });
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    initCategorySelection();
    initConfirmActions();
    initFormValidation();
});
