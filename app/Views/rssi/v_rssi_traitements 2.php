<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<div id="contenu">

    <div class="header-table">
        <h2>Tableau des traitements</h2>
        <a href="<?= site_url('pageInfo') ?>" class="btn-ajout-traitement">
            + Nouveau traitement
        </a>
    </div>

    <form method="get" class="barreRecherche">
        <input 
            type="text" 
            id="searchInput"
            autocomplete="off"
            placeholder="Recherche par nom, référence ou finalité..." 
            value="<?= esc($_GET['search'] ?? '') ?>" 
        >
        <button type="button" id="filterToggle" class="btn-filter">
            Filtres
        </button>
    </form>

    <!-- Panneau de filtres -->
    <div id="filterPanel" class="filter-panel" style="display: none;">
        <div class="filter-content">
            <div class="filter-group">
                <label>Données sensibles</label>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" class="filter-input" data-filter="sensibles" value="oui">
                        <span>Oui</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" class="filter-input" data-filter="sensibles" value="non">
                        <span>Non</span>
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <label>Transferts hors UE</label>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" class="filter-input" data-filter="transferts" value="oui">
                        <span>Oui</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" class="filter-input" data-filter="transferts" value="non">
                        <span>Non</span>
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <label>Période de création</label>
                <div class="filter-options">
                    <input type="date" id="dateDebut" class="filter-date">
                    <span style="margin: 0 8px;">à</span>
                    <input type="date" id="dateFin" class="filter-date">
                </div>
            </div>

            <div class="filter-actions">
                <button type="button" id="applyFilters" class="btn-apply-filter">Appliquer</button>
                <button type="button" id="resetFilters" class="btn-reset-filter">Réinitialiser</button>
            </div>
        </div>
    </div>

    <div id="tableContainer">

        <table class="tableTraitements">
            <thead>

                <tr>
                    <th colspan="4">Identification du traitement</th>
                    <th colspan="1">Finalité du traitement</th>
                    <th colspan="1">Données sensibles ?</th>
                    <th colspan="1">Transferts hors UE ?</th>
                </tr>

                <tr>
                    <th>Nom du traitement</th>
                    <th>N° / Réf</th>
                    <th>Date de création</th>
                    <th>Dernière mise à jour</th>
                    <th>Finalité Principale</th>
                    <th>Oui / Non</th>
                    <th>Oui / Non</th>
                </tr>

            </thead>

            <tbody id="tbodyTraitements">
                <?php foreach ($traitements as $t) : ?>
                    <tr 
                        onclick="window.location='<?= site_url('pageInfo/edit/' . $t['REF']) ?>';"
                        class="clickable-row"
                        data-sensibles="<?= strtolower($t['DONNEESSENSIBLES']) ?>"
                        data-transferts="<?= strtolower($t['TRANSFERT_HORS_UE']) ?>"
                        data-date="<?= $t['DATECREATION'] ?>"
                    >
                        <td><strong><?= esc($t['NOM']) ?></strong></td>
                        <td><code><?= esc($t['REF']) ?></code></td>
                        <td><?= esc($t['DATECREATION']) ?></td>
                        <td><?= esc($t['DATEMAJ']) ?></td>
                        <td class="finalite"><?= esc($t['FINALITE']) ?></td>
                        <td>
                            <?php if (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui'): ?>
                                <span class="badge badge-oui">Oui</span>
                            <?php else: ?>
                                <span class="badge badge-non">Non</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (trim(strtolower($t['TRANSFERT_HORS_UE'])) === 'oui' || $t['TRANSFERT_HORS_UE'] == 1): ?>
                                <span class="badge badge-oui">Oui</span>
                            <?php else: ?>
                                <span class="badge badge-non">Non</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <script>
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
    </script>

</div>

<?= $this->endSection() ?>
