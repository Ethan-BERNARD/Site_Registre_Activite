function ajouterSuppression(finalite) {
    finalite.querySelector('.supprimer').addEventListener('click', function () {
        finalite.remove();
    });
}

function ajouterSuppressionCategorie(categorie) {
    categorie.querySelector('.supprimer-categorie').addEventListener('click', function () {
        categorie.remove();
    });
}

function ajouterSuppressionSensible(sensible) {
    sensible.querySelector('.supprimer-sensible').addEventListener('click', function () {
        sensible.remove();
    });
}

function ajouterSuppressionPersonne(personne) {
    personne.querySelector('.supprimer-personne').addEventListener('click', function () {
        personne.remove();
    });
}

function ajouterSuppressionDest(dest) {
    dest.querySelector('.supprimer-destinataire').addEventListener('click', function () {
        dest.remove();
    });
}

function ajouterSuppressionSecurite(securite) {
    securite.querySelector('.supprimer-securite').addEventListener('click', function () {
        securite.remove();
    });
}

function ajouterSuppressionTransfert(transfert) {
    transfert.querySelector('.supprimer-transfert').addEventListener('click', function () {
        transfert.remove();
    });
}

function ajouterSuppressionActeur(acteur) {
    acteur.querySelector('.supprimer-acteur').addEventListener('click', function () {
        acteur.remove();
    });
}

document.addEventListener('DOMContentLoaded', function () {

    /* ---------------------- FINALITÉS ---------------------- */

    const finalitesContainer = document.getElementById('finalites-container');
    const finaliteTemplate = document.getElementById('finalite-template');

    function ajouterFinalite() {
        const clone = finaliteTemplate.content.cloneNode(true);
        const finalite = clone.querySelector('.finalite');

        ajouterSuppression(finalite);
        finalitesContainer.appendChild(finalite);
    }

    if (finalitesContainer.children.length === 0) {
        ajouterFinalite();
    }

    document.getElementById('addFinalite').addEventListener('click', ajouterFinalite);



    /* ---------------------- CATÉGORIES ---------------------- */

    const categoriesContainer = document.getElementById('categories-container');
    const categorieTemplate = document.getElementById('categorie-template');

    function ajouterCategorie() {
        const clone = categorieTemplate.content.cloneNode(true);
        const categorie = clone.querySelector('.categorie');

        ajouterSuppressionCategorie(categorie);
        categoriesContainer.appendChild(categorie);
    }

    if (categoriesContainer.children.length === 0) {
        ajouterCategorie();
    }

    document.getElementById('addCategorie').addEventListener('click', ajouterCategorie);



    /* ---------------------- SENSIBLE ---------------------- */

    const sensiblesContainer = document.getElementById('sensibles-container');
    const sensibleTemplate = document.getElementById('sensible-template');

    function ajouterSensible() {
        const clone = sensibleTemplate.content.cloneNode(true);
        const sensible = clone.querySelector('.sensible');

        ajouterSuppressionSensible(sensible);
        sensiblesContainer.appendChild(sensible);
    }

    if (sensiblesContainer.children.length === 0) {
        ajouterSensible();
    }

    document.getElementById('addSensible').addEventListener('click', ajouterSensible);



    /* ---------------------- PERSONNES ---------------------- */

    const personnesContainer = document.getElementById('personnes-container');
    const personneTemplate = document.getElementById('personne-template');

    function ajouterPersonne() {
        const clone = personneTemplate.content.cloneNode(true);
        const personne = clone.querySelector('.personne');

        ajouterSuppressionPersonne(personne);
        personnesContainer.appendChild(personne);
    }

    if (personnesContainer.children.length === 0) {
        ajouterPersonne();
    }

    document.getElementById('addPersonne').addEventListener('click', ajouterPersonne);



    /* ---------------------- DESTINATAIRES ---------------------- */

    const destContainer = document.getElementById('destinataires-container');
    const destTemplate = document.getElementById('destinataire-template');

    function ajouterDestinataire() {
        const clone = destTemplate.content.cloneNode(true);
        const dest = clone.querySelector('.destinataire');

        ajouterSuppressionDest(dest);
        destContainer.appendChild(dest);
    }

    if (destContainer.children.length === 0) {
        ajouterDestinataire();
    }

    document.getElementById('addDestinataire').addEventListener('click', ajouterDestinataire);



    /* ---------------------- SECURITE ---------------------- */

    const securiteContainer = document.getElementById('securite-container');
    const securiteTemplate = document.getElementById('securite-template');

    function ajouterSecurite() {
        const clone = securiteTemplate.content.cloneNode(true);
        const securite = clone.querySelector('.securite');

        ajouterSuppressionSecurite(securite);
        securiteContainer.appendChild(securite);
    }

    if (securiteContainer.children.length === 0) {
        ajouterSecurite();
    }

    document.getElementById('addSecurite').addEventListener('click', ajouterSecurite);



    /* ---------------------- TRANSFERT ---------------------- */

    const transfertContainer = document.getElementById('transfert-container');
    const transfertTemplate = document.getElementById('transfert-template');

    function ajouterTransfert() {
        const clone = transfertTemplate.content.cloneNode(true);
        const transfert = clone.querySelector('.transfert');

        ajouterSuppressionTransfert(transfert);
        transfertContainer.appendChild(transfert);
    }

    if (transfertContainer.children.length === 0) {
        ajouterTransfert();
    }

    document.getElementById('addTransfert').addEventListener('click', ajouterTransfert);



    /* ---------------------- ACTEUR ---------------------- */

    const acteursContainer = document.getElementById('acteurs-container');
    const acteurTemplate = document.getElementById('acteur-template');

    function ajouterActeur() {
        const clone = acteurTemplate.content.cloneNode(true);
        const acteur = clone.querySelector('.acteur');

        ajouterSuppressionActeur(acteur);
        acteursContainer.appendChild(acteur);
    }

    if (acteursContainer.children.length === 0) {
        ajouterActeur();
    }

    document.getElementById('addActeur').addEventListener('click', ajouterActeur);
});