<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<div id="contenu">

    <!-- Bouton retour intuitif -->
    <a href="<?= site_url('/rssi/tableau') ?>" class="btn-retour">
        Retour au tableau
    </a>

    <div class="header-titre">
        <h2><?= $mode === 'edit' ? 'Modifier un traitement' : 'Créer un traitement' ?></h2>
    </div>

    <form class="formInfos" method="post" action="<?= site_url('/rssi/save') ?>">

        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="id_traitement" value="<?= $traitement['REF'] ?? '' ?>">

        <div class="bloc-section">
            <h2 class="barre">Description du traitement</h2>
            <div class="static-inputs">
                <div class="ligne">
                    <label>Nom du traitement</label>
                    <input type="text" name="nom" value="<?= $traitement['NOM'] ?? '' ?>" required>
                </div>
                <div class="ligne">
                    <label>Date de création</label>
                    <input type="date" name="date_crea" value="<?= $traitement['DATECREATION'] ?? date('Y-m-d') ?>" readonly>
                </div>
                <div class="ligne">
                    <label>Date de mise à jour</label>
                    <input type="date" name="date_maj" value="<?= $traitement['DATEMAJ'] ?? date('Y-m-d') ?>" readonly>
                </div>
                <div class="ligne checkbox-ligne">
                    <label for="checkboxTransfert">Transfert hors UE</label>
                    <input type="checkbox" name="checkboxTransfert" id="checkboxTransfert"
                        <?= !empty($traitement['TRANSFERTHHORSUE']) ? 'checked' : '' ?>>
                </div>
            </div>
        </div>

        <div class="sections-grid-wrapper">

            <div class="bloc-section">
                <h2 class="barre">Acteurs</h2>
                <div id="acteurs-container" class="grid-2-col"></div>
                <button type="button" id="addActeur" class="btn-add">+ Ajouter un acteur</button>

                <template id="acteur-template">
                    <div class="acteur card-item">
                        <button type="button" class="supprimer-acteur btn-close-linux">&times;</button>
                        <div class="ligne"><label>Nom</label><input type="text" name="acteur_nom[]" required></div>
                        <div class="ligne"><label>Adresse</label><input type="text" name="acteur_adresse[]" required></div>
                        <div class="ligne"><label>Code Postal</label><input type="text" name="acteur_cp[]" required></div>
                        <div class="ligne"><label>Ville</label><input type="text" name="acteur_ville[]" required></div>
                        <div class="ligne"><label>Pays</label><input type="text" name="acteur_pays[]" required></div>
                        <div class="ligne"><label>Téléphone</label><input type="tel" name="acteur_tel[]" required></div>
                        <div class="ligne"><label>Mail</label><input type="email" name="acteur_mail[]" required></div>
                        <div class="ligne">
                            <label>Type d'acteur</label>
                            <select name="acteur_type[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Finalités</h2>
                <div id="finalites-container" class="grid-2-col"></div>
                <button type="button" id="addFinalite" class="btn-add">+ Ajouter une finalité</button>

                <template id="finalite-template">
                    <div class="finaliteBloc card-item" >
                        <button type="button" class="supprimer btn-close-linux">&times;</button>
                        <div class="ligne"><label>Finalité</label><input type="text" name="finalite[]" required></div>
                        <div class="ligne checkbox-ligne"><label>Est principal</label><input type="checkbox" name="est_principal[]"></div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Catégories de données personnelles</h2>
                <div id="categories-container" class="grid-2-col"></div>
                <button type="button" id="addCategorie" class="btn-add">+ Ajouter une catégorie</button>

                <template id="categorie-template">
                    <div class="categorie card-item">
                        <button type="button" class="supprimer-categorie btn-close-linux">&times;</button>
                        <div class="ligne"><label>Description</label><input type="text" name="categorie_description[]" required></div>
                        <div class="ligne"><label>Durée conservation</label><input type="number" name="categorie_duree[]" required></div>
                        <div class="ligne">
                            <label>Catégorie</label>
                            <select name="categorie_type[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Données sensibles</h2>
                <div id="sensibles-container" class="grid-2-col"></div>
                <button type="button" id="addSensible" class="btn-add">+ Ajouter une donnée sensible</button>

                <template id="sensible-template">
                    <div class="sensible card-item">
                        <button type="button" class="supprimer-sensible btn-close-linux">&times;</button>
                        <div class="ligne"><label>Description</label><input type="text" name="sensible_description[]" required></div>
                        <div class="ligne"><label>Durée conservation</label><input type="number" name="sensible_duree[]" required></div>
                        <div class="ligne">
                            <label>Catégorie</label>
                            <select name="sensible_categorie[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Personnes concernées</h2>
                <div id="personnes-container" class="grid-2-col"></div>
                <button type="button" id="addPersonne" class="btn-add">+ Ajouter une catégorie</button>

                <template id="personne-template">
                    <div class="personne card-item">
                        <button type="button" class="supprimer-personne btn-close-linux">&times;</button>
                        <div class="ligne">
                            <label>Catégorie</label>
                            <select name="personne_description[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="personne_precision[]" required></div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Destinataires</h2>
                <div id="destinataires-container" class="grid-2-col"></div>
                <button type="button" id="addDestinataire" class="btn-add">+ Ajouter un destinataire</button>

                <template id="destinataire-template">
                    <div class="destinataire card-item">
                        <button type="button" class="supprimer-destinataire btn-close-linux">&times;</button>
                        <div class="ligne">
                            <label>Type</label>
                            <select name="destinataire_description[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="destinataire_precision[]" required></div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Mesures de sécurité</h2>
                <div id="securite-container" class="grid-2-col"></div>
                <button type="button" id="addSecurite" class="btn-add">+ Ajouter une mesure</button>

                <template id="securite-template">
                    <div class="securite card-item">
                        <button type="button" class="supprimer-securite btn-close-linux">&times;</button>
                        <div class="ligne">
                            <label>Type</label>
                            <select name="securite_description[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="securite_precision[]" required></div>
                    </div>
                </template>
            </div>

            <div class="bloc-section" id="blocTransfert" style="display:none;">
                <h2 class="barre">Transferts hors UE</h2>
                <div id="transfert-container" class="grid-2-col"></div>
                <button type="button" id="addTransfert" class="btn-add">+ Ajouter un transfert</button>

                <template id="transfert-template">
                    <div class="transfert card-item">
                        <button type="button" class="supprimer-transfert btn-close-linux">&times;</button>
                        <div class="ligne"><label>Destinataire</label><input type="text" name="transfert_destinataire[]" required></div>
                        <div class="ligne">
                            <label>Pays</label>
                            <select name="transfert_pays[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                        <div class="ligne">
                            <label>Garantie</label>
                            <select name="transfert_garantie[]" required><option value="" disabled selected>-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Lien doc.</label><input type="text" name="transfert_lien[]" required></div>
                    </div>
                </template>
            </div>

        </div>

        <div class="submit-area">
            <input type="submit" class="btn-validate" value="Enregistrer">
        </div>

    </form>

    <!-- MODALE DE VALIDATION FINALITÉ -->
    <div id="modaleFinalite" class="modale-overlay">
        <div class="modale-container">
            <div class="modale-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 class="modale-titre">Finalité requise</h3>
            <p class="modale-message">
                Vous devez ajouter au moins une finalité avant d'enregistrer le traitement.
                <br><br>
                La finalité est obligatoire selon le RGPD.
            </p>
            <div class="modale-actions">
                <button type="button" class="btn-modal-retour" onclick="fermerModaleFinalite()">
                    Compris
                </button>
            </div>
        </div>
    </div>

    <script>
        // Passage des variables PHP au JS
        const categDCP = <?= json_encode($categDCP) ?>;
        const categDCPSensible = <?= json_encode($categDCPSensible) ?>;
        const personnesConcerne = <?= json_encode($personnesConcerne) ?>;
        const typeActeur = <?= json_encode($typeActeur) ?>;
        const typeMesureSecurite = <?= json_encode($typeMesureSecurite) ?>;
        const typeDestinataire = <?= json_encode($typeDestinataire) ?>;
        const typeGarantie = <?= json_encode($typeGarantie) ?>;
        const pays = <?= json_encode($pays) ?>;

        const mode = "<?= $mode ?>";
        const acteursData = <?= json_encode($acteurs ?? []) ?>;
        const finalitesData = <?= json_encode($finalites ?? []) ?>;
        const categoriesData = <?= json_encode($categories ?? []) ?>;
        const sensiblesData = <?= json_encode($sensibles ?? []) ?>;
        const personnesData = <?= json_encode($personnes ?? []) ?>;
        const destinatairesData = <?= json_encode($destinataires ?? []) ?>;
        const securitesData = <?= json_encode($securites ?? []) ?>;
        const transfertsData = <?= json_encode($transferts ?? []) ?>;

        // Fonction pour fermer la modale
        function fermerModaleFinalite() {
            document.getElementById('modaleFinalite').style.display = 'none';
        }

        // Fonction pour afficher la modale
        function afficherModaleFinalite() {
            document.getElementById('modaleFinalite').style.display = 'flex';
        }

        // Validation du formulaire
        document.querySelector('form').addEventListener('submit', function (e) {
            const blocs = document.querySelectorAll('.finaliteBloc');
            if (blocs.length === 0) {
                e.preventDefault();
                afficherModaleFinalite();
            }
        });

        // Fermer la modale en cliquant sur l'overlay
        document.getElementById('modaleFinalite').addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModaleFinalite();
            }
        });

        // Fermer la modale avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                fermerModaleFinalite();
            }
        });
    </script>

    <script src="<?= base_url('js/traitements_detail.js') ?>"></script>

</div>

<?= $this->endSection() ?>