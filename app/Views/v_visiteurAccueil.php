<?= $this->extend('l_visiteur') ?>

<?= $this->section('body') ?>

<h2>Gestion des frais des visiteurs</h2>

<div id="notify">
    Bienvenue <?= esc($identite) ?>, vous êtes connecté en tant que <strong>Utilisateur</strong>.
</div>

<p>
    Bienvenue dans votre application de gestion des frais de déplacements.
    Vous pouvez y renseigner vos dépenses professionnelles et suivre l’état de vos remboursements.
</p>

<h3>Fonctionnement des fiches de frais</h3>

<ul>
    <li>Une fiche couvre la période du 1er au dernier jour du mois.</li>
    <li>Les fiches sont créées automatiquement au fil de votre utilisation.</li>
    <li>Vous pouvez les compléter à votre rythme.</li>
    <li>Une fois complètes, vous devez les <strong>signer</strong> pour validation par le service comptable.</li>
</ul>

<h3>Navigation</h3>
<p>Le menu à gauche vous permet d’accéder aux fonctionnalités :</p>

<ul>
    <li>Gérer vos fiches de frais (modifier, signer, imprimer)</li>
    <li>Se déconnecter</li>
</ul>

<?= $this->endSection() ?>