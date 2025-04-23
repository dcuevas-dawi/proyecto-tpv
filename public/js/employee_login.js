// This script handles the employee login functionality
document.addEventListener('DOMContentLoaded', function() {
    const pinInput = document.getElementById('employee_pin');
    const numpadButtons = document.querySelectorAll('.numpad-key');
    const clearButton = document.querySelector('.clear-pin');

    // Check button pressed and append to input
    numpadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            pinInput.value += value;
        });
    });

    // Clear the input
    clearButton.addEventListener('click', function() {
        pinInput.value = '';
    });

});
