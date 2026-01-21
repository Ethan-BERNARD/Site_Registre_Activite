<?= $this->extend('l_RSSI') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<h2>Tableau des traitements</h2>

<div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
    <input type="text" placeholder="Recherche..." style="flex-grow: 1;">
    <button class="validate">Tag ▼</button>
</div>

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

        <!-- Ligne vide (sera remplie automatiquement plus tard) à faire avec le php-->
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td></td>

            <td></td>
        </tr>

        <!-- Exemple CNIL -->
        <tr>
            <td>Gestion de la paie</td>
            <td>1 - Exemple</td>
            <td>26/05/18</td>
            <td>13/05/19</td>

            <td>Gestion de la paie, calcul des rémunérations…</td>

            <td>Non</td>
        </tr>

    </tbody>
</table>

<?= $this->endSection() ?>