<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Intranet – Registre des Activités de Traitement</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="<?= site_url('css/styles.css') ?>" />

    <script>
        function hideNotify() {
            const notif = document.getElementById("notify");
            if (notif) notif.style.display = "none";
        }
    </script>
</head>

<body>
    <div id="page">

        <!-- En-tête -->
        <div id="entete">
            <h1>Registre des Activités de Traitement – Espace RSSI</h1>
        </div>

        <!-- Corps principal : menu + contenu -->
        <div id="corps">

            <!-- Menu latéral -->
            <div id="menuGauche">
                <div id="infosUtil">
                    <h2>
                        RSSI : 
                        <?= esc($identite) ?>
                    </h2>
                </div>

                <ul id="menuList">
                    <li class="smenu">
                        <?= anchor('rssi/', 'Accueil', 'title="Accueil RSSI"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('gestionTraitement', 'Gestion des traitements', 'title="Liste des activités de traitement"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('rssi/historique', 'Historique des modifications', 'title="Historique des actions"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('rssi/exportPDF', 'Exporter la fiche PDF', 'title="Génération automatique du PDF"') ?>
                    </li>

                    <br>

                    <li class="smenu">
                        <?= anchor('rssi/seDeconnecter', 'Se déconnecter', 'title="Déconnexion"') ?>
                    </li>
                </ul>
            </div>

            <!-- Zone dynamique -->
            <div id="contenu">
                <?= $this->renderSection('body') ?>
            </div>

        </div>

        <!-- Pied de page -->
        <div id="pied"></div>

    </div>
</body>

</html>