<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Journal des actions</h2>

    <div id="notify">
        Historique des opérations effectuées dans le système.
    </div>

    <p>
        Cette page vous permet de consulter toutes les actions réalisées par les utilisateurs :
        modifications, suppressions, validations, exports, etc.
    </p>

    <h3>Historique détaillé</h3>

    <!-- Sélecteur du nombre de lignes -->
    <div style="margin-bottom: 15px; display:flex; align-items:center; gap:10px;">
        <label for="limitSelect"><strong>Nombre de lignes :</strong></label>
        <select id="limitSelect" class="form-select" style="width:150px;">
            <option value="20" <?= ($limit == 20 ? 'selected' : '') ?>>20</option>
            <option value="50" <?= ($limit == 50 ? 'selected' : '') ?>>50</option>
            <option value="100" <?= ($limit == 100 ? 'selected' : '') ?>>100</option>
            <option value="all" <?= ($limit == 'all' ? 'selected' : '') ?>>Tout</option>
        </select>
    </div>

    <!-- Bloc déroulant -->
    <div class="card">
        <div id="logCollapse" class="collapse show">
            <div class="card-body">
                <div class="log-wrapper">

                    <table id="tableLogs">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Détails</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($logs)) : ?>
                                <?php foreach ($logs as $log) : ?>
                                    <tr>
                                        <td><?= esc($log['DATEMODIFICATION']) ?></td>
                                        <td><?= esc($log['LOGIN']) ?></td>
                                        <td><?= esc($log['TYPEACTION']) ?></td>
                                        <td><?= esc($log['DETAILS']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">Aucun log enregistré pour le moment.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h3>Informations</h3>
    <p>
        Les logs sont enregistrés automatiquement à chaque action importante afin d’assurer
        la traçabilité et la conformité RGPD.
    </p>

</div>

<!-- JS pour recharger selon le nombre -->
<script>
document.getElementById('limitSelect').addEventListener('change', function () {
    const limit = this.value;
    window.location.href = "?limit=" + limit;
});
</script>

<?= $this->endSection() ?>