<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Espace RSN</h2>

    <div id="notify">
        👋 <strong>Bienvenue <?= esc($identite) ?></strong> — Vous êtes connecté en tant que <strong>RSN</strong>.
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

        <!-- Carte 2 : Données sensibles -->
        <div class="dashboard-card card-orange">
            <div class="card-icon">⚠️</div>
            <div class="card-label">Données sensibles</div>
            <div class="card-value"><?= $stats['traitements_sensibles'] ?? 0 ?></div>
            <div class="card-subtitle">Traitements concernés</div>
        </div>

        <!-- Carte 3 : Transferts hors UE -->
        <div class="dashboard-card card-red">
            <div class="card-icon">🌍</div>
            <div class="card-label">Transferts hors UE</div>
            <div class="card-value"><?= $stats['transferts_hors_ue'] ?? 0 ?></div>
            <div class="card-subtitle">À surveiller</div>
        </div>

        <!-- Carte 4 : Dernière mise à jour -->
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
                    <a href="<?= site_url('gestionTraitement') ?>">Gérer les traitements</a>
                </td>
                <td>Créer, modifier ou supprimer une activité de traitement</td>
            </tr>
            <tr>
                <td class="action-cell">
                    <a href="#">Contrôle RGPD</a>
                </td>
                <td>Vérifier la conformité des données saisies</td>
            </tr>
            <tr>
                <td class="action-cell">
                    <a href="<?= site_url('logs') ?>">Historique</a>
                </td>
                <td>Consulter les modifications effectuées</td>
            </tr>
            <tr>
                <td class="action-cell">
                    <a href="<?= site_url('rssi/exportPDF') ?>">Export PDF</a>
                </td>
                <td>Générer automatiquement une fiche récapitulative</td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 40px;">Navigation rapide</h3>
    <div class="navigation-grid">
        
        <a href="<?= site_url('gestionTraitement') ?>" class="nav-card">
            <div class="nav-icon">📋</div>
            <div class="nav-title">Gestion</div>
            <div class="nav-desc">Liste des traitements</div>
        </a>

        <a href="<?= site_url('logs') ?>" class="nav-card">
            <div class="nav-icon">📜</div>
            <div class="nav-title">Historique</div>
            <div class="nav-desc">Suivi des modifications</div>
        </a>

        <a href="<?= site_url('rssi/exportPDF') ?>" class="nav-card">
            <div class="nav-icon">📄</div>
            <div class="nav-title">Export</div>
            <div class="nav-desc">Génération PDF</div>
        </a>

        <a href="<?= site_url('pageInfo') ?>" class="nav-card nav-card-primary">
            <div class="nav-icon">➕</div>
            <div class="nav-title">Nouveau</div>
            <div class="nav-desc">Créer un traitement</div>
        </a>

    </div>

</div>
<?= $this->endSection() ?>
