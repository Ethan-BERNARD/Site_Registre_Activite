<?= $this->extend('l_RSSI') ?>

<?= $this->section('body') ?>

<div id="contenu">

    <h2>Résultat de l’export PDF</h2>

    <div id="notify">
        <?php if ($mode === 'global') : ?>
            La fiche PDF globale a été générée (simulation).
        <?php else : ?>
            La fiche PDF du traitement <strong><?= esc($traitement['NOM']) ?></strong> a été générée (simulation).
        <?php endif; ?>
    </div>

    <p>
        Ceci est une page temporaire qui confirme la réception des données.
        Plus tard, elle sera remplacée par le téléchargement automatique du PDF.
    </p>

    <p>
        <a href="<?= site_url('rssi/exportPDF') ?>" class="validate">Retour à l’export</a>
    </p>

</div>

<?= $this->endSection() ?>