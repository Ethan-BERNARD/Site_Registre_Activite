<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('body') ?>
<div id="contenu">

    <h2>Export PDF</h2>

    <div id="notify">
        Génération automatique de la fiche récapitulative au format PDF.
    </div>

    <p>
        Cette page vous permet d’exporter une fiche PDF conforme au modèle actuel du 
        <strong>Registre des Activités de Traitement</strong>.
    </p>

    <h3>Choix du traitement</h3>

    <form action="<?= site_url('rssi/genererPDF') ?>" method="post">

        <p>
            <label for="idTraitement">Traitement :</label>
            <select name="idTraitement" id="idTraitement">
                <option value="all">Tous les traitements</option>

                <?php foreach ($traitements as $t) : ?>
                    <option value="<?= $t['REF'] ?>">
                        <?= esc($t['NOM']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <button type="submit" class="validate">Générer le PDF</button>
        </p>

    </form>

    <h3>Informations</h3>
    <p>
        Le PDF généré reprend l’ensemble des informations obligatoires du RGPD :
        finalité, responsable, catégories de données, durée de conservation, 
        destinataires, mesures de sécurité, etc.
    </p>

</div>
<?= $this->endSection() ?>