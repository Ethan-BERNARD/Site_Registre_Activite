<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>

<div id="contenu">

    <h2>Résultat de l’export PDF</h2>

    <div id="notify">
        <?php if ($mode === 'global') : ?>
            La fiche PDF globale a été générée.
        <?php else : ?>
            La fiche PDF du traitement <strong><?= esc($traitement['NOM']) ?></strong> a été générée.
        <?php endif; ?>
    </div>

    <p>
        <a href="<?= site_url('rssi/exportPDF') ?>" class="validate">Retour à l’export</a>
    </p>

</div>

<?= $this->endSection() ?>