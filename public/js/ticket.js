// Set styles for the ticket

window.onload = function() {
    // Printing options
    const style = document.createElement('style');
    style.textContent = `
        @page {
            size: 80mm auto !important;
            margin: 0mm !important;
        }
    `;
    document.head.appendChild(style);

    const mediaQueryList = window.matchMedia('print');
    mediaQueryList.addListener(function(mql) {
        if (!mql.matches) {
            // After printing or canceling
            setTimeout(function() {
                window.close();
            }, 100);
        }
    });

    setTimeout(function() {
        window.print();
    }, 200);
};
