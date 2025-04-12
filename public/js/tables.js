function incrementQuantity(button) {
    const input = button.previousElementSibling;
    input.value = parseInt(input.value) + 1;
}

function decrementQuantity(button) {
    const input = button.nextElementSibling;
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function incrementAddQuantity() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decrementAddQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const closeOrderForm = document.getElementById('closeOrderForm');

    if (closeOrderForm) {
        closeOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Enviar el formulario mediante fetch para procesar el cierre sin redirección
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
                        // Abrir el ticket en una nueva ventana
                        window.open(data.ticket_url, '_blank');

                        // Recargar la página actual para mostrar la mesa como libre
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
