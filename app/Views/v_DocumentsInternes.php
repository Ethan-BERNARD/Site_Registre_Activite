<?= $this->extend('l_visiteur') ?>

<?= $this->section('body') ?>

<div id="contenu">

    <h2>Documents internes</h2>

    <div id="notify">
        Documents mis à disposition par le RSSI pour consultation.
    </div>

    <p>
        Ces ressources vous permettent de comprendre le fonctionnement du 
        <strong>Registre des Activités de Traitement</strong> et les obligations liées au RGPD.
    </p>

    <table class="table-docs">
        <thead>
            <tr>
                <th>Document</th>
                <th>Description</th>
                <th>Téléchargement</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Modèle de fiche récapitulative (exemple)</td>
                <td>Exemple officiel utilisé pour les exports PDF</td>
                <td><a href="<?= site_url('docs/modele_fiche_traitement.pdf') ?>" target="_blank">Télécharger</a></td>
            </tr>
            <tr>
                <td>Guide RGPD interne (exemple)</td>
                <td>Règles et bonnes pratiques pour l’équipe éducative</td>
                <td><a href="<?= site_url('docs/guide_rgpd.pdf') ?>" target="_blank">Télécharger</a></td>
            </tr>
            <tr>
                <td>Procédure de déclaration d’incident (exemple)</td>
                <td>Étapes à suivre en cas de fuite ou anomalie</td>
                <td><a href="<?= site_url('docs/procedure_incident.pdf') ?>" target="_blank">Télécharger</a></td>
            </tr>
            <tr>
                <td>Politique de sécurité (exemple)</td>
                <td>Mesures de protection des données au sein du lycée</td>
                <td><a href="<?= site_url('docs/politique_securite.pdf') ?>" target="_blank">Télécharger</a></td>
            </tr>
            <tr>
                <td>Ancien registre (Excel) (exemple)</td>
                <td>Version tableur utilisée avant l’informatisation</td>
                <td><a href="<?= site_url('docs/ancien_registre.xlsx') ?>" target="_blank">Télécharger</a></td>
            </tr>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>