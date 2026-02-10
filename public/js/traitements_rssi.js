/**
 * GESTION DU TABLEAU DES TRAITEMENTS
 * - Recherche AJAX avec debounce
 * - Sélection multiple avec checkboxes
 * - Export PDF sélectif
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========== RECHERCHE AJAX ==========
    const searchInput = document.getElementById('searchInput');
    const btnExport = document.getElementById('btnExportSelection');
    const checkboxAll = document.getElementById('checkAll');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimer);
            let q = this.value;

            debounceTimer = setTimeout(() => {
                fetch(baseUrl + "/gestionTraitement/searchAjax?q=" + encodeURIComponent(q) + "&_=" + Date.now())
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('tbodyTraitements').innerHTML = html;
                        // Réattacher les événements après rechargement AJAX
                        attachCheckboxEvents();
                        updateExportButton();
                    });
            }, 300);
        });
    }

    // ========== GESTION DES CHECKBOXES ==========
    
    // Cocher/décocher tout
    if (checkboxAll) {
        checkboxAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.checkbox-traitement');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateExportButton();
        });
    }

    // Attacher les événements aux checkboxes individuelles
    function attachCheckboxEvents() {
        const checkboxes = document.querySelectorAll('.checkbox-traitement');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateCheckAllState();
                updateExportButton();
            });
        });
    }

    // Mettre à jour l'état du "cocher tout"
    function updateCheckAllState() {
        if (!checkboxAll) return;
        
        const checkboxes = document.querySelectorAll('.checkbox-traitement');
        const checkedCount = document.querySelectorAll('.checkbox-traitement:checked').length;
        
        checkboxAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        checkboxAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
    }

    // Activer/désactiver le bouton export
    function updateExportButton() {
        if (!btnExport) return;
        
        const checkedCount = document.querySelectorAll('.checkbox-traitement:checked').length;
        btnExport.disabled = checkedCount === 0;
        
        if (checkedCount > 0) {
            btnExport.textContent = `Exporter (${checkedCount})`;
        } else {
            btnExport.textContent = 'Exporter la sélection';
        }
    }

    // ========== EXPORT PDF ==========
    if (btnExport) {
        btnExport.addEventListener('click', function() {
            const selected = [];
            document.querySelectorAll('.checkbox-traitement:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un traitement');
                return;
            }

            // Créer un formulaire pour POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = baseUrl + '/rssi/genererPDF';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selection';
            input.value = JSON.stringify(selected);
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    }

    // Initialisation
    attachCheckboxEvents();
    updateExportButton();
});
