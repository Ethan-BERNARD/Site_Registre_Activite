<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Intranet – Utilisateur</title>

    <link rel="stylesheet" href="<?= site_url('css/styles.css') ?>">

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
        <header id="entete">
            <h1>Intranet – Espace Utilisateur</h1>
        </header>

        <div id="corps">

            <!-- Menu latéral -->
            <aside id="menuGauche">
                <div id="infosUtil">
                    <h2>
                        Utilisateur : <?= esc($identite) ?>
                    </h2>
                </div>

                <ul id="menuList">
                    <li class="smenu">
                        <?= anchor('utilisateur/', 'Accueil', 'title="Accueil utilisateur"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('utilisateur/documents', 'Documents internes', 'title="Documents internes"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('utilisateur/communications', 'Communications RSSI', 'title="Communications"') ?>
                    </li>

                    <br>

                    <li class="smenu">
                        <?= anchor('utilisateur/seDeconnecter', 'Se déconnecter', 'title="Déconnexion"') ?>
                    </li>
                </ul>
            </aside>

            <!-- Contenu dynamique -->
            <main id="contenu">
                <?= $this->renderSection('body') ?>
            </main>

        </div>

        <footer id="pied"></footer>

    </div>
</body>

</html>