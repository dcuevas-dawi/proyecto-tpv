document.addEventListener('DOMContentLoaded', function() {
    const pinInput = document.getElementById('employee_pin');
    const numpadButtons = document.querySelectorAll('.numpad-key');
    const clearButton = document.querySelector('.clear-pin');

    numpadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            pinInput.value += value;
        });
    });

    clearButton.addEventListener('click', function() {
        pinInput.value = '';
    });

});
