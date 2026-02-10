<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<div id="contenu">

    <div class="header-table">
        <h2>Tableau des traitements</h2>
        <div class="header-actions">
            <button id="btnExportSelection" class="btn-export-selection" disabled>
                Exporter la sélection
            </button>
            <a href="<?= site_url('/rssi/create') ?>" class="btn-ajout-traitement">
                + Nouveau traitement
            </a>
        </div>
    </div>

    <form method="get" class="barreRecherche" onsubmit="return false;">
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
                    <th colspan="5">Identification du traitement</th>
                    <th colspan="1">Finalité du traitement</th>
                    <th colspan="1">Données sensibles ?</th>
                    <th colspan="1">Transferts hors UE ?</th>
                </tr>

                <tr>
                    <th><input type="checkbox" id="checkAll" title="Tout sélectionner"></th>
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
                        onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';"
                        class="clickable-row"
                        data-sensibles="<?= strtolower($t['DONNEESSENSIBLES']) ?>"
                        data-transferts="<?= strtolower($t['TRANSFERT_HORS_UE']) ?>"
                        data-date="<?= $t['DATECREATION'] ?>"
                    >
                        <td onclick="event.stopPropagation();">
                            <input 
                                type="checkbox" 
                                class="checkbox-traitement" 
                                value="<?= esc($t['REF']) ?>"
                            >
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <strong><?= esc($t['NOM']) ?></strong>
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <code><?= esc($t['REF']) ?></code>
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <?= esc($t['DATECREATION']) ?>
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <?= esc($t['DATEMAJ']) ?>
                        </td>
                        <td class="finalite" onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <?= esc($t['FINALITE']) ?>
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
                            <?php if (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui'): ?>
                                <span class="badge badge-oui">Oui</span>
                            <?php else: ?>
                                <span class="badge badge-non">Non</span>
                            <?php endif; ?>
                        </td>
                        <td onclick="window.location='<?= site_url('/rssi/edit/' . $t['REF']) ?>';">
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

    <script src="<?= base_url('js/filtreRecherche.js') ?>"></script>
    <script>const baseUrl = '<?= base_url() ?>';</script>
    <script src="<?= base_url('js/traitements_rssi.js') ?>"></script>

    <!--pop-up enregistrement-->
    <?php if (session()->getFlashdata('success')) : ?>
        <div id="toast-success" class="toast">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <script>
    const toast = document.getElementById('toast-success');
    if (toast) {
        setTimeout(() => toast.classList.add('show'), 200); // apparition
        setTimeout(() => toast.classList.remove('show'), 3000); // disparition
    }
    </script>

</div>

<?= $this->endSection() ?>
