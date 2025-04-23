// Product Management

// Edit and delete confirmations
function initConfirmActions() {
    // Function for delete confirmation
    window.confirmDelete = function(button) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            button.closest('form').submit();
        }
    };

    // Function for restore confirmation
    window.confirmRestore = function(button) {
        if (confirm('¿Estás seguro de que deseas restaurar este producto?')) {
            button.closest('form').submit();
        }
    };
}

// Initialize form validation
function initFormValidation() {
    const productForm = document.getElementById('product-form');

    if (!productForm) {
        console.log('No se encontró el formulario de producto');
        return;
    }

    // Add error elements if they don't exist
    ['name', 'price', 'category', 'description'].forEach(field => {
        if (!document.getElementById(`${field}-error`)) {
            const errorSpan = document.createElement('span');
            errorSpan.id = `${field}-error`;
            errorSpan.className = 'text-red-500 text-xs hidden';
            const fieldElement = document.getElementById(field);
            if (fieldElement && fieldElement.parentNode) {
                fieldElement.parentNode.appendChild(errorSpan);
            }
        }
    });

    productForm.addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        let firstError = null;

        // Name validation
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('name-error');

        if (nameInput && nameError) {
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
        }

        // Price validation
        const priceInput = document.getElementById('price');
        const priceError = document.getElementById('price-error');

        if (priceInput && priceError) {
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
        }

        // Category validation
        const categorySelect = document.getElementById('category');
        const categoryError = document.getElementById('category-error');

        if (categorySelect && categoryError) {
            if (!categorySelect.value) {
                categoryError.textContent = 'Debes seleccionar una categoría';
                categoryError.classList.remove('hidden');
                isValid = false;
                firstError = firstError || categorySelect;
            } else {
                categoryError.classList.add('hidden');
            }
        }

        // Description validation (optional)
        const descriptionInput = document.getElementById('description');
        const descriptionError = document.getElementById('description-error');

        if (descriptionInput && descriptionError) {
            if (descriptionInput.value.length > 500) {
                descriptionError.textContent = 'La descripción no puede tener más de 500 caracteres';
                descriptionError.classList.remove('hidden');
                isValid = false;
                firstError = firstError || descriptionInput;
            } else {
                descriptionError.classList.add('hidden');
            }
        }

        // If there are errors, prevent submission and scroll to the first error
        if (!isValid) {
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        } else {
            // If the form is valid, submit it
            productForm.submit();
        }
    });

    // Validation for inputs before submission
    const inputs = productForm.querySelectorAll('input, textarea, select');
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
    initConfirmActions();
    initFormValidation();
});
