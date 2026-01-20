<!-- HTMX -->
<script src="https://unpkg.com/htmx.org@1.9.10"
    integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC"
    crossorigin="anonymous"></script>

<!-- Toastify -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- Global Admin Scripts -->
<script>
    // Global Toast Function
    window.toast = function (message, type = 'info') {
        let bg = "#333";
        if (type === 'success') bg = "#2ecc71";
        if (type === 'error') bg = "#e74c3c";
        if (type === 'warning') bg = "#f39c12";

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: bg,
            stopOnFocus: true
        }).showToast();
    }

    // HTMX Events for Toast
    document.body.addEventListener('htmx:afterSwap', function (evt) {
        // If server returns a header with toast message (Custom implementation if needed)
    });

    // Auto-init specific behaviors if needed
</script>