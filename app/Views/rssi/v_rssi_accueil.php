<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Espace RSSI</h2>

    <div id="notify">
        Bienvenue <?= esc($identite) ?>, vous êtes connecté en tant que <strong>RSSI</strong>.
    </div>

    <p>
        Cet espace est dédié à la gestion du <strong>Registre des Activités de Traitement</strong> de l’établissement.
        Vous pouvez suivre, documenter et garantir la conformité RGPD des traitements réalisés.
    </p>

    <h3>Vos missions principales</h3>
    <table>
        <thead>
            <tr>
                <th>Action</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gérer les traitements</a></td>
                <td>Créer, modifier ou supprimer une activité de traitement</td>
            </tr>
            <tr>
                <td>Contrôle RGPD</td>
                <td>Vérifier la conformité des données saisies</td>
            </tr>
            <tr>
                <td>Historique</td>
                <td>Consulter les modifications effectuées</td>
            </tr>
            <tr>
                <td>Export PDF</td>
                <td>Générer automatiquement une fiche récapitulative</td>
            </tr>
        </tbody>
    </table>

    <h3>Navigation</h3>
    <p>Utilisez le menu latéral pour accéder aux différentes fonctionnalités :</p>
    <ul>
        <li><strong>Accueil</strong> – cette page</li>
        <li><strong>Gestion des traitements</strong> – liste et actions</li>
        <li><strong>Historique</strong> – suivi des modifications</li>
        <li><strong>Exporter PDF</strong> – génération automatique</li>
        <li><strong>Déconnexion</strong> – quitter l’espace sécurisé</li>
    </ul>

</div>
<?= $this->endSection() ?>