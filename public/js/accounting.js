// This script handles the date range selection for the accounting form

document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const periodSelect = document.getElementById('period');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const form = document.getElementById('accountingForm');

    // Initial values
    let defaultStartDate = startDateInput.value;
    let defaultEndDate = endDateInput.value;

    // By default, select "Daily" and the current date if there are no values
    if (!periodSelect.value) {
        periodSelect.value = 'daily';
        updateDateRanges();
    }

    // Function to update date ranges according to the period
    function updateDateRanges() {
        const now = new Date();
        let startDate = new Date(now);
        let endDate = new Date(now);

        switch(periodSelect.value) {
            case 'daily':
                // Today (no changes)
                break;

            case 'weekly':
                // Start of the week (Monday)
                const day = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
                const diff = day === 0 ? 6 : day - 1; // Adjust so that the week starts on Monday
                startDate.setDate(now.getDate() - diff);
                // End of the week (Sunday)
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                break;

            case 'monthly':
                // Start of the month
                startDate.setDate(1);
                // End of the month
                endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                break;

            case 'quarterly':
                // Determine the current quarter
                const quarter = Math.floor(now.getMonth() / 3);
                // Start of the quarter
                startDate = new Date(now.getFullYear(), quarter * 3, 1);
                // End of the quarter
                endDate = new Date(now.getFullYear(), (quarter + 1) * 3, 0);
                break;

            case 'yearly':
                // Start of the year
                startDate = new Date(now.getFullYear(), 0, 1);
                // End of the year
                endDate = new Date(now.getFullYear(), 11, 31);
                break;
        }

        // Format dates for the input type="date"
        startDateInput.value = formatDateForInput(startDate);
        endDateInput.value = formatDateForInput(endDate);

        // Save the updated default dates
        defaultStartDate = startDateInput.value;
        defaultEndDate = endDateInput.value;
    }

    // Format YYYY-MM-DD for date inputs
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Update dates when the period changes
    periodSelect.addEventListener('change', function() {
        updateDateRanges();
        form.submit(); // Submit form automatically
    });

    // Initialize dates if they don't have values yet
    if (!startDateInput.value || !endDateInput.value) {
        updateDateRanges();
    }
});
