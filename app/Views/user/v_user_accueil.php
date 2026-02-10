<?= $this->extend('layouts/l_user') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Espace Utilisateur</h2>

    <div id="notify">
        👋 <strong>Bienvenue <?= esc($identite) ?></strong> — Vous êtes connecté en tant qu'<strong>Utilisateur</strong>.
    </div>

    <!-- Statistiques en cartes -->
    <h3>Tableau de bord</h3>
    <div class="dashboard-grid">
        
        <!-- Carte 1 : Traitements -->
        <div class="dashboard-card card-blue">
            <div class="card-icon">📋</div>
            <div class="card-label">Traitements</div>
            <div class="card-value"><?= $stats['total_traitements'] ?? 0 ?></div>
            <div class="card-subtitle">Total enregistrés</div>
        </div>
        <!-- Carte 2 : Dernière mise à jour -->
        <div class="dashboard-card card-green">
            <div class="card-icon">🕐</div>
            <div class="card-label">Dernière action</div>
            <div class="card-value">
                <?php 
                if (!empty($stats['derniere_action'])) {
                    $date = new DateTime($stats['derniere_action']['date']);
                    echo $date->format('d/m/Y');
                } else {
                    echo 'Aucune';
                }
                ?>
            </div>
            <div class="card-subtitle">
                <?= !empty($stats['derniere_action']) ? esc($stats['derniere_action']['type']) : 'Pas d\'activité récente' ?>
            </div>
        </div>

    </div>

    <h3>Vos missions principales</h3>
    <table class="table-missions">
        <thead>
            <tr>
                <th>Action</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="action-cell">
                    <a href="#">Liste des traitements</a>
                </td>
                <td>Consulter les traitements enregistrés</td>
            </tr>
            <tr>
                <td class="action-cell">
                    <a href="#">Ajout d'un traitement</a>
                </td>
                <td>Possibilité de créer un nouveau traitement</td>
            </tr>
        </tbody>
    </table>
    <h3 style="margin-top: 40px;">Navigation rapide</h3>
    <div class="navigation-grid">
        
        <a href="<?= site_url('/user/tableau') ?>" class="nav-card">
            <div class="nav-icon">📋</div>
            <div class="nav-title">Gestion</div>
            <div class="nav-desc">Liste des traitements</div>
        </a>

        <a href="<?= site_url('/user/creer') ?>" class="nav-card nav-card-primary">
            <div class="nav-icon">➕</div>
            <div class="nav-title">Nouveau</div>
            <div class="nav-desc">Créer un traitement</div>
        </a>

    </div>

</div>
<?= $this->endSection() ?>
