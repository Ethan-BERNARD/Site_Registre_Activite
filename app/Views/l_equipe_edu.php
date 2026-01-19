<!DOCTYPE html>
<html lang="fr">

	<head>
		<title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="<?= site_url('css/styles.css') ?>" />

		<script language="JavaScript">
			function hideNotify() {
				document.getElementById("notify").style.display = "none";
			}
		</script>
		
	</head>

	<body onload="setTimeout(hideNotify,7000);">
		<div id="page">
			<div id="entete">
				<img src="<?= site_url('images/logo.jpg') ?>" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
				<h1>Gestion du remboursement des frais</h1>
			</div>
			
			<!-- Division pour le menu -->
			<div id="menuGauche">
				<div id="infosUtil">
					<h2>
						Visiteur :<br/>
						<?= $identite ?>
					</h2>
				</div>  
				
				<ul id="menuList">
					<li class="smenu">
						<?= anchor('visiteur/', 'Accueil', 'title="Page d\'accueil"') ?>
					</li>
					<li class="smenu">
						<?= anchor('visiteur/mesFiches', 'Mes fiches de frais', 'title="Consultation de mes fiches de frais"') ?>
					</li>
					<br/>
					<li class="smenu">
						<?= anchor('visiteur/seDeconnecter', 'Se déconnecter', 'title="Déconnexion"') ?>
					</li>
				</ul>
				
			</div>

			<?= $this->renderSection('body') ?>

			<div id="pied">
				<br/>
			</div>

		</div>    

	</body>
</html>