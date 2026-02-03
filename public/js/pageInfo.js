document.addEventListener("DOMContentLoaded", () => {

    /* ---------------------------------------------------------
       OUTILS GÉNÉRIQUES
    --------------------------------------------------------- */

    function fillSelect(select, data, valueField, labelField) {
        if (!select || !data) return;
        data.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item[valueField];
            opt.textContent = item[labelField];
            select.appendChild(opt);
        });
    }

    function gestionBloc(btnId, containerId, templateId, classSupprimer, callback = null) {
        const container = document.getElementById(containerId);
        const template = document.getElementById(templateId);
        const btn = document.getElementById(btnId);

        if (!container || !template || !btn) return;

        function ajouter() {
            const clone = template.content.cloneNode(true);
            const element = clone.querySelector("*");

            const btnSuppr = element.querySelector(classSupprimer);
            if (btnSuppr) btnSuppr.addEventListener("click", () => element.remove());

            if (callback) callback(element);

            container.appendChild(element);
        }

        if (!container.children.length) ajouter();

        btn.addEventListener("click", ajouter);
    }

    function prefillBloc(dataArray, addButtonId, containerSelector, fillCallback) {
        if (mode !== "edit" || !dataArray?.length) return;

        const addBtn = document.getElementById(addButtonId);
        const container = document.querySelector(containerSelector);

        if (!addBtn || !container) return;

        dataArray.forEach(item => {
            addBtn.click();
            const element = container.lastElementChild;
            fillCallback(element, item);
        });
    }

    /* ---------------------------------------------------------
       INITIALISATION DES BLOCS
    --------------------------------------------------------- */

    gestionBloc("addActeur", "acteurs-container", "acteur-template", ".supprimer-acteur", el => {
        fillSelect(el.querySelector('select[name="acteur_type[]"]'), typeActeur, "IDTYPE", "LIBELLE");
    });

    gestionBloc("addFinalite", "finalites-container", "finalite-template", ".supprimer");

    gestionBloc("addCategorie", "categories-container", "categorie-template", ".supprimer-categorie", el => {
        fillSelect(el.querySelector('select[name="categorie_type[]"]'), categDCP, "IDCATEG", "LIBELLE");
    });

    gestionBloc("addSensible", "sensibles-container", "sensible-template", ".supprimer-sensible", el => {
        fillSelect(el.querySelector('select[name="sensible_categorie[]"]'), categDCPSensible, "IDCATEG", "LIBELLE");
    });

    gestionBloc("addPersonne", "personnes-container", "personne-template", ".supprimer-personne", el => {
        fillSelect(el.querySelector('select[name="personne_description[]"]'), personnesConcerne, "ID", "LIBELLE");
    });

    gestionBloc("addDestinataire", "destinataires-container", "destinataire-template", ".supprimer-destinataire", el => {
        fillSelect(el.querySelector('select[name="destinataire_description[]"]'), typeDestinataire, "ID", "LIBELLE");
    });

    gestionBloc("addSecurite", "securite-container", "securite-template", ".supprimer-securite", el => {
        fillSelect(el.querySelector('select[name="securite_description[]"]'), typeMesureSecurite, "ID", "LIBELLE");
    });

    gestionBloc("addTransfert", "transfert-container", "transfert-template", ".supprimer-transfert", el => {
        fillSelect(el.querySelector('select[name="transfert_pays[]"]'), pays, "ID", "NOMPAYS");
        fillSelect(el.querySelector('select[name="transfert_garantie[]"]'), typeGarantie, "ID", "LIBELLE");
    });

    /* ---------------------------------------------------------
       AFFICHAGE / MASQUAGE TRANSFERT
    --------------------------------------------------------- */

    const checkbox = document.getElementById("checkboxTransfert");
    const blocTransfert = document.getElementById("blocTransfert");

    if (checkbox && blocTransfert) {
        checkbox.addEventListener("change", () => {
            blocTransfert.style.display = checkbox.checked ? "block" : "none";
            if (checkbox.checked && !document.querySelector("#transfert-container").children.length) {
                document.getElementById("addTransfert").click();
            }
        });

        if (mode === "edit" && checkbox.checked) {
            blocTransfert.style.display = "block";
        }
    }

    /* ---------------------------------------------------------
       PRÉREMPLISSAGE
    --------------------------------------------------------- */

    prefillBloc(acteursData, "addActeur", "#acteurs-container", (el, item) => {
        el.querySelector('input[name="acteur_nom[]"]').value = item.NOM;
        el.querySelector('input[name="acteur_adresse[]"]').value = item.ADRESSE;
        el.querySelector('input[name="acteur_cp[]"]').value = item.CP;
        el.querySelector('input[name="acteur_ville[]"]').value = item.VILLE;
        el.querySelector('input[name="acteur_pays[]"]').value = item.PAYS;
        el.querySelector('input[name="acteur_tel[]"]').value = item.TEL;
        el.querySelector('input[name="acteur_mail[]"]').value = item.MAIL;
        el.querySelector('select[name="acteur_type[]"]').value = item.IDTYPE;
    });

    prefillBloc(finalitesData, "addFinalite", "#finalites-container", (el, item) => {
        el.querySelector('input[name="finalite[]"]').value = item.LIBELLE;
        el.querySelector('input[name="est_principal[]"]').checked = item.ESTPRINCIPAL == 1;
    });

    prefillBloc(categoriesData, "addCategorie", "#categories-container", (el, item) => {
        el.querySelector('input[name="categorie_description[]"]').value = item.DESCRIPTION;
        el.querySelector('input[name="categorie_duree[]"]').value = item.DUREECONSERVATION;
        el.querySelector('select[name="categorie_type[]"]').value = item.IDCATEG;
    });

    prefillBloc(sensiblesData, "addSensible", "#sensibles-container", (el, item) => {
        el.querySelector('input[name="sensible_description[]"]').value = item.DESCRIPTION;
        el.querySelector('input[name="sensible_duree[]"]').value = item.DUREECONSERVATION;
        el.querySelector('select[name="sensible_categorie[]"]').value = item.IDCATEG;
    });

    prefillBloc(personnesData, "addPersonne", "#personnes-container", (el, item) => {
        el.querySelector('select[name="personne_description[]"]').value = item.ID_EST_DE_CATEGORIE_PERSONNE;
        el.querySelector('input[name="personne_precision[]"]').value = item.PRECIS;
    });

    prefillBloc(destinatairesData, "addDestinataire", "#destinataires-container", (el, item) => {
        el.querySelector('select[name="destinataire_description[]"]').value = item.ID_EST_DE_TYPE_DESTINATAIRE;
        el.querySelector('input[name="destinataire_precision[]"]').value = item.PRECIS;
    });

    prefillBloc(securitesData, "addSecurite", "#securite-container", (el, item) => {
        el.querySelector('select[name="securite_description[]"]').value = item.ID_EST_DE_TYPE_DE_MESURE;
        el.querySelector('input[name="securite_precision[]"]').value = item.PRECIS;
    });

    prefillBloc(transfertsData, "addTransfert", "#transfert-container", (el, item) => {
        el.querySelector('input[name="transfert_destinataire[]"]').value = item.DESTINATAIRE;
        el.querySelector('select[name="transfert_pays[]"]').value = item.ID_TRANSFERT_VERS_PAYS;
        el.querySelector('select[name="transfert_garantie[]"]').value = item.ID_GARANTIE_APPLIQUEE;
        el.querySelector('input[name="transfert_lien[]"]').value = item.LIENDOC;
    });

});
