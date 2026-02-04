<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<h2>Tableau des traitements</h2>

<form method="get" class="barreRecherche">
    <input 
        type="text" 
        id="searchInput"
        autocomplete="off"
        placeholder="Recherche..." 
        value="<?= esc($_GET['search'] ?? '') ?>" 
    >
    <button type="button" class="validate">Tag ▼</button>
</form>

<div id="tableContainer">

    <table class="tableTraitements">
        <thead>

            <!-- Ligne 1 -->
            <tr>
                <th colspan="4">Identification du traitement</th>
                <th colspan="1">Finalité du traitement</th>
                <th colspan="1">Transferts hors UE ?</th>
                <th colspan="1">Données sensibles ?</th>
            </tr>

            <!-- Ligne 2 -->
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
                    onclick="window.location='<?= site_url('gestionTraitement/detail/' . $t['REF']) ?>';" 
                    class="clickable-row"
                >
                    <td><?= esc($t['NOM']) ?></td>
                    <td><?= esc($t['REF']) ?></td>
                    <td><?= esc($t['DATECREATION']) ?></td>
                    <td><?= esc($t['DATEMAJ']) ?></td>
                    <td class="finalite"><?= esc($t['FINALITE']) ?></td>
                    <td><?= esc($t['TRANSFERT_HORS_UE']) ?></td>
                    <td><?= esc($t['DONNEESSENSIBLES']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
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
</script>

<?= $this->endSection() ?>