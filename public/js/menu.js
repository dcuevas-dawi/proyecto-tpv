// Function to update clock
function updateClock() {
    const now = new Date();

    // Format time: HH:MM:SS
    const time = now.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    // Format date: Day of week, Day Month Year
    const date = now.toLocaleDateString('es-ES', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    // Update DOM
    document.getElementById('current-time').textContent = time;
    document.getElementById('current-date').textContent = date.charAt(0).toUpperCase() + date.slice(1);
}

// Update immediately and then every second
updateClock();
setInterval(updateClock, 1000);
