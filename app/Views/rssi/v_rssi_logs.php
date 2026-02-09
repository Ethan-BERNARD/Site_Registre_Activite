<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Journal des actions</h2>

    <div id="notify">
        Historique des opérations effectuées dans le système.
    </div>

    <h3>Historique détaillé</h3>

    <div style="margin-bottom: 15px; display:flex; align-items:center; gap:10px;">
        <label for="limitSelect"><strong>Nombre de lignes :</strong></label>
        <select id="limitSelect" class="form-select" style="width:150px; padding: 8px 12px; border-radius: 6px; border: 2px solid #d1d5db;">
            <option value="20" <?= ($limit == 20 ? 'selected' : '') ?>>20</option>
            <option value="50" <?= ($limit == 50 ? 'selected' : '') ?>>50</option>
            <option value="100" <?= ($limit == 100 ? 'selected' : '') ?>>100</option>
            <option value="all" <?= ($limit == 'all' ? 'selected' : '') ?>>Tout</option>
        </select>
    </div>

    <div id="tableContainerLogs">
        <table id="tableLogs" class="table-logs-sticky">
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
                            <td class="text-muted"><?= esc($log['DATEMODIFICATION']) ?></td>
                            <td><strong><?= esc($log['LOGIN']) ?></strong></td>
                            <td>
                                <?php 
                                    $actionClass = 'badge-default';
                                    $action = strtoupper($log['TYPEACTION']);
                                    if (strpos($action, 'EXPORT') !== false) $actionClass = 'badge-ue';
                                    if (strpos($action, 'SUPPR') !== false || strpos($action, 'DELETE') !== false) $actionClass = 'badge-oui';
                                    if (strpos($action, 'AJOUT') !== false || strpos($action, 'CREATE') !== false) $actionClass = 'badge-non';
                                ?>
                                <span class="badge <?= $actionClass ?>"><?= esc($action) ?></span>
                            </td>
                            <td class="text-left"><?= esc($log['DETAILS']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">Aucun log enregistré pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3>Informations</h3>
    <p>
        Les logs sont enregistrés automatiquement à chaque action importante afin d'assurer
        la traçabilité et la conformité RGPD.
    </p>

</div>

<script>
document.getElementById('limitSelect').addEventListener('change', function () {
    const limit = this.value;
    window.location.href = "?limit=" + limit;
});
</script>

<?= $this->endSection() ?>
