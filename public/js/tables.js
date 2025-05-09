// Functionality for tables and orders management

document.addEventListener('DOMContentLoaded', function() {
    // Handlers for updating product quantities
    setupQuantityButtons();

    // Handler for closing order
    setupCloseOrderForm();
});

// Set up increment/decrement quantity buttons
function setupQuantityButtons() {
    // Get all increment and decrement buttons
    const increaseButtons = document.querySelectorAll('.increase-quantity');
    const decreaseButtons = document.querySelectorAll('.decrease-quantity');

    // Add event listeners to increment buttons
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this, 'increase');
        });
    });

    // Add event listeners to decrement buttons
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this, 'decrease');
        });
    });
}

// Function to update product quantity
function updateQuantity(button, action) {
    const form = button.closest('.quantity-form');
    const input = form.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);

    if (action === 'increase') {
        input.value = currentValue + 1;
    } else if (action === 'decrease' && currentValue > 1) {
        input.value = currentValue - 1;
    } else {
        return; // No changes if trying to decrease below 1
    }

    // Send the form automatically
    form.submit();
}
