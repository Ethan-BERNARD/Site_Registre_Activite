document.addEventListener("DOMContentLoaded", () => {

    // Toggle filter panel
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            const panel = document.getElementById('filterPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });
    }

    // Apply filters
    const applyFilters = document.getElementById('applyFilters');
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            const rows = document.querySelectorAll('#tbodyTraitements tr');
            
            // Get selected filters
            const sensiblesFilters = Array.from(document.querySelectorAll('[data-filter="sensibles"]:checked')).map(cb => cb.value);
            const transfertsFilters = Array.from(document.querySelectorAll('[data-filter="transferts"]:checked')).map(cb => cb.value);
            const dateDebut = document.getElementById('dateDebut').value;
            const dateFin = document.getElementById('dateFin').value;
            
            rows.forEach(row => {
                let show = true;
                
                // Filter by sensibles
                if (sensiblesFilters.length > 0) {
                    const sensibles = row.dataset.sensibles;
                    if (!sensiblesFilters.includes(sensibles)) show = false;
                }
                
                // Filter by transferts
                if (transfertsFilters.length > 0) {
                    const transferts = row.dataset.transferts;
                    if (!transfertsFilters.includes(transferts)) show = false;
                }
                
                // Filter by date range
                if (dateDebut && row.dataset.date < dateDebut) show = false;
                if (dateFin && row.dataset.date > dateFin) show = false;
                
                row.style.display = show ? '' : 'none';
            });
        });
    }

    // Reset filters
    const resetFilters = document.getElementById('resetFilters');
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            document.querySelectorAll('.filter-input').forEach(cb => cb.checked = false);
            document.getElementById('dateDebut').value = '';
            document.getElementById('dateFin').value = '';
            document.querySelectorAll('#tbodyTraitements tr').forEach(row => row.style.display = '');
        });
    }
});