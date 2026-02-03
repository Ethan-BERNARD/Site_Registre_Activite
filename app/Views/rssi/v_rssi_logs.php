<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Journal des actions</h2>

    <div id="notify">
        Historique des opérations effectuées dans le système.
    </div>

    <p>
        Cette page vous permet de consulter toutes les actions réalisées par les utilisateurs :
        connexions, modifications, suppressions, validations, exports, etc.
    </p>

    <h3>Historique détaillé</h3>

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

    <h3>Informations</h3>
    <p>
        Les logs sont enregistrés automatiquement à chaque action importante afin d’assurer
        la traçabilité et la conformité RGPD.
    </p>

</div>
<?= $this->endSection() ?>