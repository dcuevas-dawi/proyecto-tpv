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

// Set up the form for closing the order
function setupCloseOrderForm() {
    const closeOrderForm = document.getElementById('closeOrderForm');

    if (closeOrderForm) {
        closeOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Send the form using fetch to process the closure without redirection
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Open the ticket in a new window
                        window.open(data.ticket_url, '_blank');

                        // Reload the current page to show the table as free
                        window.location.reload();
                    } else {
                        alert('Error al cerrar el pdido: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }
}
