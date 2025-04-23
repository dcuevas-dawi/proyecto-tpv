//
function updateQuantity(button, action) {
    const form = button.closest('.quantity-form');
    const input = form.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);

    if (action === 'increase') {
        input.value = currentValue + 1;
    } else if (action === 'decrease' && currentValue > 1) {
        input.value = currentValue - 1;
    } else {
        return; // No cambios si intenta disminuir por debajo de 1
    }

    // Enviar el formulario automáticamente
    form.submit();
}

// Event for closing the order

document.addEventListener('DOMContentLoaded', function() {
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
                        alert('Error al cerrar el pedido: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }
});
