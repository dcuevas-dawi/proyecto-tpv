// Management of product categories
function initCategorySelection() {
    const categoryOptions = document.querySelectorAll('.category-option');
    if (!categoryOptions.length) return;

    // Initielize the state
    categoryOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio.checked) {
            highlightSelected(option);
        }
    });

    // Section manager
    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Leave the selected one
            categoryOptions.forEach(opt => {
                opt.classList.remove('border-yellow-500', 'border-red-500', 'border-purple-500', 'bg-yellow-100', 'bg-red-100', 'bg-purple-100');
                opt.classList.add('border-yellow-300', 'border-red-300', 'border-purple-300');
            });

            // Highlight the selected one
            highlightSelected(this);
        });
    });
}

// Highlight the selected category option
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

// Confirmation for deleting and restoring products
function initConfirmActions() {
    // Función para confirmar eliminación
    window.confirmDelete = function(button) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            button.closest('form').submit();
        }
    };

    // Function to confirm restoration
    window.confirmRestore = function(button) {
        if (confirm('¿Estás seguro de que deseas restaurar este producto?')) {
            button.closest('form').submit();
        }
    };
}

// This function initializes the form validation
function initFormValidation() {
    const productForm = document.getElementById('product-form');
    if (!productForm) return;

    productForm.addEventListener('submit', function(e) {
        let isValid = true;
        let firstError = null;

        // Name validation
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

        // Price validation
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

        // Category validation
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

        // Description validation (optional)
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

        // If there are errors, prevent submission and scroll to the first error
        if (!isValid) {
            e.preventDefault();
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Real-time validation
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

// Initialize when the DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initCategorySelection();
    initConfirmActions();
    initFormValidation();
});
