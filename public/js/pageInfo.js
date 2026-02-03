document.addEventListener("DOMContentLoaded", function () {

    /* ---------------------------------------------------------
       FONCTION GÉNÉRIQUE POUR GÉRER LES BLOCS DYNAMIQUES
       + callback pour préremplir les selects
    --------------------------------------------------------- */

    function gestionBloc(btnId, containerId, templateId, classSupprimer, callback = null) {

        const container = document.getElementById(containerId);
        const template = document.getElementById(templateId);
        const btn = document.getElementById(btnId);

        function ajouterElement() {
            const clone = template.content.cloneNode(true);
            const element = clone.querySelector('*');

            // Bouton supprimer
            element.querySelector(classSupprimer).addEventListener("click", () => {
                element.remove();
            });

            // Callback pour remplir les selects
            if (callback) callback(element);

            container.appendChild(element);
        }

        // Vérification robuste : ignore les espaces, retours à la ligne, commentaires
        if (container.querySelector('*') === null) {
            ajouterElement();
        }

        // Bouton d'ajout
        btn.addEventListener("click", ajouterElement);
    }

    /* ---------------------------------------------------------
       REMPLISSAGE DES SELECT (adapté à ta base)
    --------------------------------------------------------- */

    function fillSelect(select, data, valueField, labelField) {
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item[valueField];
            opt.textContent = item[labelField];
            select.appendChild(opt);
        });
    }

    /* ---------------------------------------------------------
       INITIALISATION DES BLOCS AVEC LES BONS CHAMPS
    --------------------------------------------------------- */

    gestionBloc("addActeur", "acteurs-container", "acteur-template", ".supprimer-acteur",
        function (element) {
            fillSelect(
                element.querySelector('select[name="categorie_type[]"]'),
                typeActeur,
                'IDTYPE',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addFinalite", "finalites-container", "finalite-template", ".supprimer");

    gestionBloc("addCategorie", "categories-container", "categorie-template", ".supprimer-categorie",
        function (element) {
            fillSelect(
                element.querySelector('select[name="categorie_type[]"]'),
                categDCP,
                'IDCATEGDCP',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addSensible", "sensibles-container", "sensible-template", ".supprimer-sensible",
        function (element) {
            fillSelect(
                element.querySelector('select[name="sensible_categorie[]"]'),
                categDCPSensible,
                'IDCATEGDCPSENSIBLE',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addPersonne", "personnes-container", "personne-template", ".supprimer-personne",
        function (element) {
            fillSelect(
                element.querySelector('select[name="personne_description[]"]'),
                personnesConcerne,
                'IDCATEGPERSONNECONCERNE',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addDestinataire", "destinataires-container", "destinataire-template", ".supprimer-destinataire",
        function (element) {
            fillSelect(
                element.querySelector('select[name="destinataire_description[]"]'),
                typeDestinataire,
                'IDTYPE',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addSecurite", "securite-container", "securite-template", ".supprimer-securite",
        function (element) {
            fillSelect(
                element.querySelector('select[name="securite_description[]"]'),
                typeMesureSecurite,
                'IDTYPE',
                'LIBELLE'
            );
        }
    );

    gestionBloc("addTransfert", "transfert-container", "transfert-template", ".supprimer-transfert",
        function (element) {
            fillSelect(
                element.querySelector('select[name="transfert_pays[]"]'),
                pays,
                'IDPAYS',
                'NOMPAYS'
            );

            fillSelect(
                element.querySelector('select[name="transfert_garantie[]"]'),
                typeGarantie,
                'IDTYPE',
                'LIBELLE'
            );
        }
    );

    /* ---------------------------------------------------------
       AFFICHAGE / MASQUAGE DU BLOC TRANSFERT
    --------------------------------------------------------- */

    const checkbox = document.getElementById("checkboxTransfert");
    const blocTransfert = document.getElementById("blocTransfert");

    checkbox.addEventListener("change", () => {
        blocTransfert.style.display = checkbox.checked ? "block" : "none";

        if (checkbox.checked) {
            const container = document.getElementById("transfert-container");
            if (container.querySelector('*') === null) {
                document.getElementById("addTransfert").click();
            }
        }
    });

    /* ---------------------------------------------------------
        PRÉREMPLISSAGE GÉNÉRIQUE DES BLOCS
    --------------------------------------------------------- */

    function prefillBloc(dataArray, addButtonId, containerSelector, fillCallback) {
        if (mode !== "edit" || !dataArray || dataArray.length === 0) return;

        dataArray.forEach(item => {
            document.getElementById(addButtonId).click();
            const container = document.querySelector(containerSelector);
            const element = container.lastElementChild;
            fillCallback(element, item);
        });
    }

    /* ---------------------------------------------------------
        APPELS DE PRÉREMPLISSAGE POUR CHAQUE BLOC
    --------------------------------------------------------- */

    prefillBloc(acteursData, "addActeur", "#acteurs-container", (el, item) => {
        el.querySelector('input[name="acteur_nom[]"]').value = item.NOM;
        el.querySelector('input[name="acteur_adresse[]"]').value = item.ADRESSE;
        el.querySelector('input[name="acteur_cp[]"]').value = item.CP;
        el.querySelector('input[name="acteur_ville[]"]').value = item.VILLE;
        el.querySelector('input[name="acteur_pays[]"]').value = item.PAYS;
        el.querySelector('input[name="acteur_tel[]"]').value = item.TEL;
        el.querySelector('input[name="acteur_mail[]"]').value = item.MAIL;
        el.querySelector('select[name="categorie_type[]"]').value = item.IDTYPE;
    });

    prefillBloc(finalitesData, "addFinalite", "#finalites-container", (el, item) => {
        el.querySelector('input[name="finalite[]"]').value = item.FINALITE;
        el.querySelector('input[name="est_principal[]"]').checked = item.EST_PRINCIPAL == 1;
    });

    prefillBloc(categoriesData, "addCategorie", "#categories-container", (el, item) => {
        el.querySelector('input[name="categorie_description[]"]').value = item.DESCRIPTION;
        el.querySelector('input[name="categorie_duree[]"]').value = item.DUREE;
        el.querySelector('select[name="categorie_type[]"]').value = item.IDCATEGDCP;
    });

    prefillBloc(sensiblesData, "addSensible", "#sensibles-container", (el, item) => {
        el.querySelector('input[name="sensible_description[]"]').value = item.DESCRIPTION;
        el.querySelector('input[name="sensible_duree[]"]').value = item.DUREE;
        el.querySelector('select[name="sensible_categorie[]"]').value = item.IDCATEGDCPSENSIBLE;
    });

    prefillBloc(personnesData, "addPersonne", "#personnes-container", (el, item) => {
        el.querySelector('select[name="personne_description[]"]').value = item.IDCATEGPERSONNECONCERNE;
        el.querySelector('input[name="personne_precision[]"]').value = item.PRECISION;
    });

    prefillBloc(destinatairesData, "addDestinataire", "#destinataires-container", (el, item) => {
        el.querySelector('select[name="destinataire_description[]"]').value = item.IDTYPE;
        el.querySelector('input[name="destinataire_precision[]"]').value = item.PRECISION;
    });

    prefillBloc(securitesData, "addSecurite", "#securite-container", (el, item) => {
        el.querySelector('select[name="securite_description[]"]').value = item.IDTYPE;
        el.querySelector('input[name="securite_precision[]"]').value = item.PRECISION;
    });

    prefillBloc(transfertsData, "addTransfert", "#transfert-container", (el, item) => {
        el.querySelector('input[name="transfert_destinataire[]"]').value = item.DESTINATAIRE;
        el.querySelector('select[name="transfert_pays[]"]').value = item.IDPAYS;
        el.querySelector('select[name="transfert_garantie[]"]').value = item.IDTYPE;
        el.querySelector('input[name="transfert_lien[]"]').value = item.LIEN;
    });

})