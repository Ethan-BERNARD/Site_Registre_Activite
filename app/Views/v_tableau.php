<?= $this->extend('l_RSSI') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<h2>Tableau des traitements</h2>

<form method="get" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
    <input 
        type="text" 
        name="search"
        autocomplete="off"
        placeholder="Recherche..." 
        value="<?= esc($_GET['search'] ?? '') ?>" 
        style="flex-grow: 1;"
    >
    <button class="validate">Tag ▼</button>
</form>

<table class="tableTraitements">
    <thead>

        <!-- Ligne 1 : Types -->
        <tr>
            <th colspan="4">Identification du traitement</th>
            <th colspan="1">Finalité du traitement</th>
            <th colspan="1">Données sensibles ?</th>
        </tr>

        <!-- Ligne 2 : Sous-types -->
        <tr>
            <th>Nom du traitement</th>
            <th>N° / Réf</th>
            <th>Date de création</th>
            <th>Dernière mise à jour</th>

            <th>Finalité</th>

            <th>Oui / Non</th>
        </tr>

    </thead>

    <tbody>
        <?php foreach ($traitements as $t) : ?>
            <tr>
                <td><?= esc($t['NOM']) ?></td>
                <td><?= esc($t['REF']) ?></td>
                <td><?= esc($t['DATECREATION']) ?></td>
                <td><?= esc($t['DATEMAJ']) ?></td>
                <td class="finalite"><?= esc($t['FINALITE']) ?></td>
                <td><?= esc($t['DONNEESSENSIBLES']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>