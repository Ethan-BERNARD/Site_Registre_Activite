document.addEventListener("DOMContentLoaded", () => {

    // Recherche avec debounce
    let debounceTimer;
    document.getElementById('searchInput').addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        let q = this.value;

        debounceTimer = setTimeout(() => {
            fetch("<?= site_url('gestionTraitement/searchAjax') ?>?q=" + encodeURIComponent(q) + "&_=" + Date.now())
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tbodyTraitements').innerHTML = html;
                });
        }, 300);
    });

    // Toggle filter panel
    document.getElementById('filterToggle').addEventListener('click', function() {
        const panel = document.getElementById('filterPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    // Apply filters
    document.getElementById('applyFilters').addEventListener('click', function() {
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

    // Reset filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.querySelectorAll('.filter-input').forEach(cb => cb.checked = false);
        document.getElementById('dateDebut').value = '';
        document.getElementById('dateFin').value = '';
        document.querySelectorAll('#tbodyTraitements tr').forEach(row => row.style.display = '');
    });   
});