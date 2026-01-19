<?= $this->extend('l_visiteur') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Espace Utilisateur</h2>

    <div id="notify">
        Bienvenue <?= esc($identite) ?>, vous êtes connecté en tant que <strong>Utilisateur</strong>.
    </div>

    <p>
        Cet espace vous permet d’accéder aux documents internes, aux communications du RSSI
        et aux informations essentielles de l’établissement.
        Vous disposez d’un accès simplifié et sécurisé pour consulter les ressources mises à votre disposition.
    </p>

    <h3>Fonctionnalités principales</h3>
    <table>
        <thead>
            <tr>
                <th>Action</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Documents internes</td>
                <td>Consulter les documents mis à disposition par l’établissement</td>
            </tr>
            <tr>
                <td>Communications</td>
                <td>Lire les messages et annonces du RSSI</td>
            </tr>
            <tr>
                <td>Profil</td>
                <td>Mettre à jour certaines informations personnelles</td>
            </tr>
            <tr>
                <td>Support</td>
                <td>Accéder aux informations d’aide et de contact</td>
            </tr>
        </tbody>
    </table>

    <h3>Navigation</h3>
    <p>Utilisez le menu latéral pour accéder aux différentes fonctionnalités :</p>
    <ul>
        <li><strong>Accueil</strong> – cette page</li>
        <li><strong>Documents internes</strong> – accès aux fichiers</li>
        <li><strong>Communications</strong> – messages du RSSI</li>
        <li><strong>Profil</strong> – informations personnelles</li>
        <li><strong>Déconnexion</strong> – quitter l’espace sécurisé</li>
    </ul>

</div>
<?= $this->endSection() ?>

