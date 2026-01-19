<?= $this->extend('l_RSSI') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Espace RSSI</h2>

    <p>
        Bienvenue dans l’application de gestion du Registre des Activités de Traitement.
        Cet espace est dédié au suivi, à la documentation et à la conformité RGPD des traitements
        réalisés au sein de l’établissement.
    </p>

    <p>
        En tant que <strong>RSSI</strong>, vous êtes responsable de la supervision des traitements,
        de la mise à jour du registre et de la génération des fiches récapitulatives.
    </p>

    <h3>Vos missions principales</h3>
    <ul>
        <li>Gérer les activités de traitement (création, modification, suppression),</li>
        <li>Contrôler la conformité RGPD des informations saisies,</li>
        <li>Consulter l’historique des modifications,</li>
        <li>Générer automatiquement la fiche récapitulative PDF.</li>
    </ul>

    <h3>Navigation</h3>
    <p>Le menu situé à gauche vous permet d’accéder rapidement aux différentes fonctionnalités :</p>
    <ul>
        <li>Gestion des traitements</li>
        <li>Historique des modifications</li>
        <li>Export PDF</li>
        <li>Déconnexion</li>
    </ul>

</div>
<?= $this->endSection() ?>