<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Intranet – Gestion des frais</title>
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
            <h1>Gestion du remboursement des frais</h1>
        </div>

        <!-- Corps principal : menu + contenu -->
        <div id="corps">

            <!-- Menu latéral -->
            <div id="menuGauche">
                <div id="infosUtil">
                    <h2>
                        Visiteur : 
                        <?= esc($identite) ?>
                    </h2>
                </div>

                <ul id="menuList">
                    <li class="smenu">
                        <?= anchor('utilisateur/', 'Accueil', 'title="Page d\'accueil"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('utilisateur/mesFiches', 'Mes fiches de frais', 'title="Consultation de mes fiches de frais"') ?>
                    </li>

                    <br>

                    <li class="smenu">
                        <?= anchor('utilisateur/seDeconnecter', 'Se déconnecter', 'title="Déconnexion"') ?>
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