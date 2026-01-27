<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Espace d’Identification du Personnel</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="<?= site_url('css/styles.css') ?>" />
</head>

<body>
    <div id="page" class="pageConnexion">
        <div id="entete">
            <h1>Espace d’Identification du Personnel</h1>
        </div>

        <div id="corps">
            <div id="colGauche">
                <?= $this->renderSection('body') ?>
            </div>

            <div id="colDroite">
                <?= $this->renderSection('droite') ?>
            </div>
        </div>

        <div id="pied">
            <br/>
        </div>
    </div>
</body>
</html>