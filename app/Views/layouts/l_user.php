<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Registre des Activités de Traitement – Espace Utilisateur</title>

    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>" />

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
            <h1>Registre des Activités de Traitement – Espace Utilisateur</h1>
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
                        <?= anchor('user', 'Accueil', 'title="Accueil utilisateur"') ?>
                    </li>

                    <li class="smenu">
                        <?= anchor('user/tableau', 'Gestion des traitements', 'title="Liste des activités de traitement"') ?>
                    </li> 

                    <br>

                    <li class="smenu">
                        <?= anchor('user/seDeconnecter', 'Se déconnecter', 'title="Déconnexion"') ?>
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