<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement RGPD</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>

<h1><?= $mode === 'edit' ? 'Modifier un traitement' : 'Créer un traitement' ?></h1>

<form class="formInfos" method="post" action="/pageInfo/save">

    <!-- Mode + REF -->
    <input type="hidden" name="mode" value="<?= $mode ?>">
    <input type="hidden" name="id_traitement" value="<?= $traitement['REF'] ?? '' ?>">

    <!-- ---------------------------------------------------------
         DESCRIPTION DU TRAITEMENT
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Description du traitement</h2>

        <p>Nom du traitement</p>
        <input type="text" name="nom" value="<?= $traitement['NOM'] ?? '' ?>">

        <p>N° / Référence</p>
        <input type="text" name="ref" value="<?= $traitement['REF'] ?? '' ?>">

        <p>Date de création</p>
        <input type="date" name="date_crea" value="<?= $traitement['DATECREATION'] ?? '' ?>">

        <p>Date de mise à jour</p>
        <input type="date" name="date_maj" value="<?= $traitement['DATEMAJ'] ?? '' ?>">

        <p>Transfert hors UE</p>
        <input type="checkbox" name="checkboxTransfert" id="checkboxTransfert"
               <?= !empty($traitement['TRANSFERTHHORSUE']) ? 'checked' : '' ?>>
    </div>

    <!-- ---------------------------------------------------------
         ACTEURS
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Acteurs</h2>

        <div id="acteurs-container"></div>
        <button type="button" id="addActeur">Ajouter un acteur</button>

        <template id="acteur-template">
            <div class="acteur">
                <p>Nom</p>
                <input type="text" name="acteur_nom[]">

                <p>Adresse</p>
                <input type="text" name="acteur_adresse[]">

                <p>Code Postal</p>
                <input type="text" name="acteur_cp[]">

                <p>Ville</p>
                <input type="text" name="acteur_ville[]">

                <p>Pays</p>
                <input type="text" name="acteur_pays[]">

                <p>Téléphone</p>
                <input type="text" name="acteur_tel[]">

                <p>Mail</p>
                <input type="text" name="acteur_mail[]">

                <p>Type d'acteur</p>
                <select name="acteur_type[]">
                    <option value="">-- Choisir --</option>
                </select>

                <button type="button" class="supprimer-acteur">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         FINALITÉS
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Finalités</h2>

        <div id="finalites-container"></div>
        <button type="button" id="addFinalite">Ajouter une finalité</button>

        <template id="finalite-template">
            <div class="finaliteBloc">
                <p>Finalité</p>
                <input type="text" name="finalite[]">

                <p>Est principal</p>
                <input type="checkbox" name="est_principal[]">

                <button type="button" class="supprimer">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         CATÉGORIES DCP
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Catégories de données personnelles</h2>

        <div id="categories-container"></div>
        <button type="button" id="addCategorie">Ajouter une catégorie</button>

        <template id="categorie-template">
            <div class="categorie">
                <p>Description</p>
                <input type="text" name="categorie_description[]">

                <p>Durée de conservation</p>
                <input type="text" name="categorie_duree[]">

                <p>Catégorie</p>
                <select name="categorie_type[]">
                    <option value="">-- Choisir --</option>
                </select>

                <button type="button" class="supprimer-categorie">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         DONNÉES SENSIBLES
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Données sensibles</h2>

        <div id="sensibles-container"></div>
        <button type="button" id="addSensible">Ajouter une donnée sensible</button>

        <template id="sensible-template">
            <div class="sensible">
                <p>Description</p>
                <input type="text" name="sensible_description[]">

                <p>Durée de conservation</p>
                <input type="text" name="sensible_duree[]">

                <p>Catégorie</p>
                <select name="sensible_categorie[]">
                    <option value="">-- Choisir --</option>
                </select>

                <button type="button" class="supprimer-sensible">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         PERSONNES CONCERNÉES
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Personnes concernées</h2>

        <div id="personnes-container"></div>
        <button type="button" id="addPersonne">Ajouter une catégorie</button>

        <template id="personne-template">
            <div class="personne">
                <p>Catégorie</p>
                <select name="personne_description[]">
                    <option value="">-- Choisir --</option>
                </select>

                <p>Précision</p>
                <input type="text" name="personne_precision[]">

                <button type="button" class="supprimer-personne">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         DESTINATAIRES
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Destinataires</h2>

        <div id="destinataires-container"></div>
        <button type="button" id="addDestinataire">Ajouter un destinataire</button>

        <template id="destinataire-template">
            <div class="destinataire">
                <p>Type</p>
                <select name="destinataire_description[]">
                    <option value="">-- Choisir --</option>
                </select>

                <p>Précision</p>
                <input type="text" name="destinataire_precision[]">

                <button type="button" class="supprimer-destinataire">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         MESURES DE SÉCURITÉ
    --------------------------------------------------------- -->
    <div>
        <h2 class="barre">Mesures de sécurité</h2>

        <div id="securite-container"></div>
        <button type="button" id="addSecurite">Ajouter une mesure</button>

        <template id="securite-template">
            <div class="securite">
                <p>Type</p>
                <select name="securite_description[]">
                    <option value="">-- Choisir --</option>
                </select>

                <p>Précision</p>
                <input type="text" name="securite_precision[]">

                <button type="button" class="supprimer-securite">Supprimer</button>
            </div>
        </template>
    </div>

    <!-- ---------------------------------------------------------
         TRANSFERTS HORS UE
    --------------------------------------------------------- -->
    <div id="blocTransfert" style="display:none;">
        <h2 class="barre">Transferts hors UE</h2>

        <div id="transfert-container"></div>
        <button type="button" id="addTransfert">Ajouter un transfert</button>

        <template id="transfert-template">
            <div class="transfert">
                <p>Destinataire</p>
                <input type="text" name="transfert_destinataire[]">

                <p>Pays</p>
                <select name="transfert_pays[]">
                    <option value="">-- Choisir --</option>
                </select>

                <p>Garantie</p>
                <select name="transfert_garantie[]">
                    <option value="">-- Choisir --</option>
                </select>

                <p>Lien documentation</p>
                <input type="text" name="transfert_lien[]">

                <button type="button" class="supprimer-transfert">Supprimer</button>
            </div>
        </template>
    </div>

    <input type="submit" value="<?= $mode === 'edit' ? 'Modifier' : 'Enregistrer' ?>">

</form>

<!-- Données envoyées au JS -->
<script>
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

</body>
</html>