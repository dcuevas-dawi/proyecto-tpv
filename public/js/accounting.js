document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos del formulario
    const periodSelect = document.getElementById('period');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const form = document.getElementById('accountingForm');

    // Valores iniciales
    let defaultStartDate = startDateInput.value;
    let defaultEndDate = endDateInput.value;

    // Por defecto, seleccionar "Diario" y la fecha actual si no hay valores
    if (!periodSelect.value) {
        periodSelect.value = 'daily';
        updateDateRanges();
    }

    // Función para actualizar los rangos de fecha según el periodo
    function updateDateRanges() {
        const now = new Date();
        let startDate = new Date(now);
        let endDate = new Date(now);

        switch(periodSelect.value) {
            case 'daily':
                // Hoy (sin cambios)
                break;

            case 'weekly':
                // Inicio de la semana (lunes)
                const day = now.getDay(); // 0 = domingo, 1 = lunes, etc.
                const diff = day === 0 ? 6 : day - 1; // Ajustar para que la semana empiece en lunes
                startDate.setDate(now.getDate() - diff);
                // Fin de la semana (domingo)
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                break;

            case 'monthly':
                // Inicio del mes
                startDate.setDate(1);
                // Fin del mes
                endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                break;

            case 'quarterly':
                // Determinar el trimestre actual
                const quarter = Math.floor(now.getMonth() / 3);
                // Inicio del trimestre
                startDate = new Date(now.getFullYear(), quarter * 3, 1);
                // Fin del trimestre
                endDate = new Date(now.getFullYear(), (quarter + 1) * 3, 0);
                break;

            case 'yearly':
                // Inicio del año
                startDate = new Date(now.getFullYear(), 0, 1);
                // Fin del año
                endDate = new Date(now.getFullYear(), 11, 31);
                break;
        }

        // Formatear fechas para el input type="date"
        startDateInput.value = formatDateForInput(startDate);
        endDateInput.value = formatDateForInput(endDate);

        // Guardar las fechas predeterminadas actualizadas
        defaultStartDate = startDateInput.value;
        defaultEndDate = endDateInput.value;
    }

    // Formato YYYY-MM-DD para inputs date
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Actualizar fechas cuando cambia el periodo
    periodSelect.addEventListener('change', function() {
        updateDateRanges();
        form.submit(); // Enviar formulario automáticamente
    });

    // Inicializar fechas si no tienen valores aún
    if (!startDateInput.value || !endDateInput.value) {
        updateDateRanges();
    }
});
