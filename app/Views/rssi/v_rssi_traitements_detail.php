<?= $this->extend('layouts/l_rssi') ?>

<?= $this->section('title') ?>Tableau des traitements<?= $this->endSection() ?>

<?= $this->section('body') ?>

<div id="contenu">

    <!-- Bouton retour intuitif -->
    <a href="<?= site_url('gestionTraitement') ?>" class="btn-retour">
        Retour au tableau
    </a>

    <div class="header-titre">
        <h2><?= $mode === 'edit' ? 'Modifier un traitement' : 'Créer un traitement' ?></h2>
    </div>

    <form class="formInfos" method="post" action="/pageInfo/save">

        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="id_traitement" value="<?= $traitement['REF'] ?? '' ?>">

        <div class="bloc-section">
            <h2 class="barre">Description du traitement</h2>
            <div class="static-inputs">
                <div class="ligne">
                    <label>Nom du traitement</label>
                    <input type="text" name="nom" value="<?= $traitement['NOM'] ?? '' ?>">
                </div>
                <div class="ligne">
                    <label>N° / Référence</label>
                    <input type="text" name="ref" value="<?= $traitement['REF'] ?? '' ?>">
                </div>
                <div class="ligne">
                    <label>Date de création</label>
                    <input type="date" name="date_crea" value="<?= $traitement['DATECREATION'] ?? '' ?>" readonly>
                </div>
                <div class="ligne">
                    <label>Date de mise à jour</label>
                    <input type="date" name="date_maj" value="<?= $traitement['DATEMAJ'] ?? '' ?>" readonly>
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
                        <div class="ligne"><label>Nom</label><input type="text" name="acteur_nom[]"></div>
                        <div class="ligne"><label>Adresse</label><input type="text" name="acteur_adresse[]"></div>
                        <div class="ligne"><label>Code Postal</label><input type="text" name="acteur_cp[]"></div>
                        <div class="ligne"><label>Ville</label><input type="text" name="acteur_ville[]"></div>
                        <div class="ligne"><label>Pays</label><input type="text" name="acteur_pays[]"></div>
                        <div class="ligne"><label>Téléphone</label><input type="text" name="acteur_tel[]"></div>
                        <div class="ligne"><label>Mail</label><input type="text" name="acteur_mail[]"></div>
                        <div class="ligne">
                            <label>Type d'acteur</label>
                            <select name="acteur_type[]"><option value="">-- Choisir --</option></select>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bloc-section">
                <h2 class="barre">Finalités</h2>
                <div id="finalites-container" class="grid-2-col"></div>
                <button type="button" id="addFinalite" class="btn-add">+ Ajouter une finalité</button>

                <template id="finalite-template">
                    <div class="finaliteBloc card-item">
                        <button type="button" class="supprimer btn-close-linux">&times;</button>
                        <div class="ligne"><label>Finalité</label><input type="text" name="finalite[]"></div>
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
                        <div class="ligne"><label>Description</label><input type="text" name="categorie_description[]"></div>
                        <div class="ligne"><label>Durée conservation</label><input type="text" name="categorie_duree[]"></div>
                        <div class="ligne">
                            <label>Catégorie</label>
                            <select name="categorie_type[]"><option value="">-- Choisir --</option></select>
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
                        <div class="ligne"><label>Description</label><input type="text" name="sensible_description[]"></div>
                        <div class="ligne"><label>Durée conservation</label><input type="text" name="sensible_duree[]"></div>
                        <div class="ligne">
                            <label>Catégorie</label>
                            <select name="sensible_categorie[]"><option value="">-- Choisir --</option></select>
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
                            <select name="personne_description[]"><option value="">-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="personne_precision[]"></div>
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
                            <select name="destinataire_description[]"><option value="">-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="destinataire_precision[]"></div>
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
                            <select name="securite_description[]"><option value="">-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Précision</label><input type="text" name="securite_precision[]"></div>
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
                        <div class="ligne"><label>Destinataire</label><input type="text" name="transfert_destinataire[]"></div>
                        <div class="ligne">
                            <label>Pays</label>
                            <select name="transfert_pays[]"><option value="">-- Choisir --</option></select>
                        </div>
                        <div class="ligne">
                            <label>Garantie</label>
                            <select name="transfert_garantie[]"><option value="">-- Choisir --</option></select>
                        </div>
                        <div class="ligne"><label>Lien doc.</label><input type="text" name="transfert_lien[]"></div>
                    </div>
                </template>
            </div>

        </div>

        <div class="submit-area">
            <input type="submit" class="btn-validate" value="Enregistrer">
        </div>

    </form>

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
    </script>

    <script src="/js/pageInfo.js?v=2"></script>
</div>

<?= $this->endSection() ?>
