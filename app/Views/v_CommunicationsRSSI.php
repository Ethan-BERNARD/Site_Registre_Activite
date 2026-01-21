<?= $this->extend('l_visiteur') ?>

<?= $this->section('body') ?>

<div id="contenu">

    <h2>Communications du RSSI</h2>

    <div id="notify">
        Informations officielles transmises par le RSSI à l’équipe éducative.
    </div>

    <p>
        Cette page regroupe les communications internes concernant la sécurité des données, les procédures RGPD,
        et les incidents éventuels. Ces messages sont à titre informatif uniquement.
    </p>

    <table class="table-docs">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sujet</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>12/01/2026</td>
                <td>Rappel sur les mots de passe</td>
                <td>Merci de renouveler vos mots de passe tous les 90 jours. Ne les partagez jamais.</td>
            </tr>
            <tr>
                <td>05/01/2026</td>
                <td>Incident réseau</td>
                <td>Une tentative d’accès non autorisé a été détectée. Aucune donnée compromise.</td>
            </tr>
            <tr>
                <td>20/12/2025</td>
                <td>Export du registre</td>
                <td>Le registre des traitements a été mis à jour. La fiche PDF est disponible dans “Documents internes”.</td>
            </tr>
            <tr>
                <td>01/12/2025</td>
                <td>Formation RGPD</td>
                <td>Une session de sensibilisation RGPD est prévue le 15 janvier. Inscription via le secrétariat.</td>
            </tr>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>