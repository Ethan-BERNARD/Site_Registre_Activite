<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Info</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <?php if ($mode === 'edit'): ?>
        <h1>Modifier un traitement</h1>
    <?php else: ?>
        <h1>Créer un nouveau traitement</h1>
    <?php endif; ?>

    <form>
        <div>
            <h2>Description du traitement</h2>
            <p>Nom du traitement : </p>
            <input type="Text">
            <p>N°/Ref</p>
            <input type="Text">
            <p>Date de création du traitement</p>
            <input type="Date">
            <p>Mise  à jour du traitement</p>
            <input type="Date">
            <p>Transfert hors de l'UE</p>
            <input type="checkbox" id="checkboxTransfert">
        </div>

        <div>
            <h2>Acteurs</h2>
            <div id="acteurs-container">
                <!-- Les acteurs ajoutés apparaîtront ici -->
            </div>
            <button type="button" id="addActeur">Ajouter un acteur</button>
            <!-- Template invisible -->
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
                    <select name="categorie_type[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <button type="button" class="supprimer-acteur">Supprimer</button>
                </div>
            </template>
        </div>

        <div>
            <h2>Finalité(s) du traitement des données</h2>
            <div id="finalites-container">
                <!-- Finalités ajoutées ici -->
            </div>
            <button type="button" id="addFinalite">Ajouter une finalité</button>
            <!-- Modèle invisible -->
            <template id="finalite-template">
                <div class="finalite">
                    <p>Finalité</p>
                    <input type="text" name="finalite[]">

                    <p>Est principal</p>
                    <input type="checkbox" name="est_principal[]">

                    <button type="button" class="supprimer">Supprimer</button>
                </div>
            </template>
        </div>
        
        <div>
            <h2>Catégories de données personnelles concernées</h2>
            <div id="categories-container">
                <!-- Les catégories ajoutées apparaîtront ici -->
            </div>
            <button type="button" id="addCategorie">Ajouter une catégorie</button>
            <!-- Template invisible -->
            <template id="categorie-template">
                <div class="categorie">

                    <p>Description</p>
                    <input type="text" name="categorie_description[]">

                    <p>Durée de conservation</p>
                    <input type="text" name="categorie_duree[]">

                    <p>Catégorie de données personnelles concernées</p>
                    <select name="categorie_type[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <button type="button" class="supprimer-categorie">Supprimer</button>
                </div>
            </template>
        </div>
        
        <div>
            <h2>Données Sensibles</h2>
            <div id="sensibles-container">
                <!-- Les données sensibles ajoutés apparaîtront ici -->
            </div>
            <button type="button" id="addSensible">Ajouter une donnée sensible</button>
            <!-- Template invisible -->
            <template id="sensible-template">
                <div class="sensible">
                    <p>Description</p>
                    <input type="text" name="sensible_description[]">
                    <p>Durée de conservation</p>
                    <input type="text" name="sensible_duree[]">
                    <p>Catégorie de données sensible</p>
                    <select name="sensible_categorie[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <button type="button" class="supprimer-sensible">Supprimer</button>
                </div>
            </template>
        </div>

        <div>
            <h2>Catégories de personnes concernées</h2>
            <div id="personnes-container">
                <!-- Les personnes ajoutés apparaîtront ici -->
            </div>
            <button type="button" id="addPersonne">Ajouter une catégorie de personne</button>
            <!-- Template invisible -->
            <template id="personne-template">
                <div class="personne">
                    <p>Description</p>
                    <select name="personne_description[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <p>Précision</p>
                    <input type="text" name="personne_precision[]">
                    <button type="button" class="supprimer-personne">Supprimer</button>
                </div>
            </template>
        </div>

        <div>
            <h2>Destinataires</h2>
            <div id="destinataires-container">
                <!-- Les destinataires ajoutés apparaîtront ici -->
            </div>
            <button type="button" id="addDestinataire">Ajouter un destinataire</button>
            <!-- Template invisible -->
            <template id="destinataire-template">
                <div class="destinataire">

                    <p>Description</p>
                    <select name="destinataire_description[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <p>Précision</p>
                    <input type="text" name="destinataire_precision[]">
                    <button type="button" class="supprimer-destinataire">Supprimer</button>
                </div>
            </template>
        </div>

        <div>
            <h2>Mesures de sécurité</h2>
            <div id="securite-container">
                <!-- Les mesures ajoutées apparaîtront ici -->
            </div>
            <button type="button" id="addSecurite">Ajouter une mesure de sécurité</button>
            <!-- Template invisible -->
            <template id="securite-template">
                <div class="securite">
                    <p>Description</p>
                    <select name="securite_description[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <p>Précision</p>
                    <input type="text" name="securite_precision[]">
                    <button type="button" class="supprimer-securite">Supprimer</button>
                </div>
            </template>
        </div>

        <div id="blocTransfert" style="display:none;">
            <h2>Transfert hors UE</h2>
            <div id="transfert-container">
                <!-- Les transferts ajoutés apparaîtront ici -->
            </div>
            <button type="button" id="addTransfert">Ajouter un transfert</button>
            <!-- Template invisible -->
            <template id="transfert-template">
                <div class="transfert">
                    <p>Destinataire</p>
                    <input type="text" name="transfert_destinataire[]">
                    <p>Pays</p>
                    <select name="transfert_pays[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <p>Type de garantie</p>
                    <select name="transfert_garantie[]">
                        <option value="">-- Choisir --</option>
                    </select>
                    <p>Lien vers la documentation</p>
                    <input type="text" name="transfert_lien[]">
                    <button type="button" class="supprimer-transfert">Supprimer</button>
                </div>
            </template>
        </div>
        <!-- envoie du type de formulaire (création / modification) -->
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="id_traitement" value="<?= $traitement['ID'] ?? '' ?>">

        <?php if ($mode === 'edit'): ?>
            <input type="submit" value="Modifier">
        <?php else: ?>
            <input type="submit" value="Enregistrer">
        <?php endif; ?>
    </form>

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
        const traitement = <?= json_encode($traitement) ?>;

        const acteursData = <?= json_encode($acteurs ?? []) ?>;
        const finalitesData = <?= json_encode($finalites ?? []) ?>;
        const categoriesData = <?= json_encode($categories ?? []) ?>;
        const sensiblesData = <?= json_encode($sensibles ?? []) ?>;
        const personnesData = <?= json_encode($personnes ?? []) ?>;
        const destinatairesData = <?= json_encode($destinataires ?? []) ?>;
        const securitesData = <?= json_encode($securites ?? []) ?>;
        const transfertsData = <?= json_encode($transferts ?? []) ?>;

    </script>
    <script src="/js/pageInfo.js?v=1"></script>

</body>