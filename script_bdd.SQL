-- =====================================================
-- SCRIPT DE CRÉATION BASE DE DONNÉES - REGISTRE RGPD
-- Version sécurisée avec optimisations
-- =====================================================

DROP DATABASE IF EXISTS REGISTRE_DCP;
CREATE DATABASE IF NOT EXISTS REGISTRE_DCP CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE REGISTRE_DCP;

-- TABLE : FINALITE
CREATE TABLE IF NOT EXISTS FINALITE (
    IDFINALITE INT AUTO_INCREMENT NOT NULL,
    REF INT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    ESTPRINCIPAL BOOLEAN DEFAULT 0,
    PRIMARY KEY (IDFINALITE),
    INDEX I_FK_FINALITE_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TYPEDEGARANTIE
CREATE TABLE IF NOT EXISTS TYPEDEGARANTIE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LISTEDESTINATAIRE
CREATE TABLE IF NOT EXISTS LISTEDESTINATAIRE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_EST_DE_TYPE_DESTINATAIRE INT NOT NULL,
    REF INT NOT NULL,
    PRECIS VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE (ID_EST_DE_TYPE_DESTINATAIRE ASC),
    INDEX I_FK_LISTEDESTINATAIRE_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LISTEDCP
CREATE TABLE IF NOT EXISTS LISTEDCP (
    ID INT AUTO_INCREMENT NOT NULL,
    IDCATEG INT NOT NULL,
    REF INT NOT NULL,
    DESCRIPTION VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    DUREECONSERVATION INT NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LISTEDCP_CATEGDCP (IDCATEG ASC),
    INDEX I_FK_LISTEDCP_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : PAYS
CREATE TABLE IF NOT EXISTS PAYS (
    ID INT AUTO_INCREMENT NOT NULL,
    NOMPAYS VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID),
    INDEX IDX_PAYS_NOM (NOMPAYS(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LISTEPERSONNECONCERNE
CREATE TABLE IF NOT EXISTS LISTEPERSONNECONCERNE (
    ID INT AUTO_INCREMENT NOT NULL,
    REF INT NOT NULL,
    ID_EST_DE_CATEGORIE_PERSONNE INT NOT NULL,
    PRECIS VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LISTEPERSONNECONCERNE_TRAITEMENT (REF ASC),
    INDEX I_FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE (ID_EST_DE_CATEGORIE_PERSONNE ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LOG (AVEC AMÉLIORATIONS)
CREATE TABLE IF NOT EXISTS LOG (
    ID INT AUTO_INCREMENT NOT NULL,
    UTILISATEUR_ID INT NULL,  -- NULL au lieu de NOT NULL pour gérer les triggers sans @user_id
    TYPEACTION VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    DETAILS TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    DATEMODIFICATION DATETIME NOT NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LOG_UTILISATEURS (UTILISATEUR_ID ASC),
    INDEX IDX_LOG_DATE (DATEMODIFICATION DESC),
    INDEX IDX_LOG_TYPE (TYPEACTION(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TYPEMESURESECURITE
CREATE TABLE IF NOT EXISTS TYPEMESURESECURITE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TYPEDESTINATAIRE
CREATE TABLE IF NOT EXISTS TYPEDESTINATAIRE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LISTEDCPSENSIBLE
CREATE TABLE IF NOT EXISTS LISTEDCPSENSIBLE (
    ID INT AUTO_INCREMENT NOT NULL,
    IDCATEG INT NOT NULL,
    REF INT NOT NULL,
    DESCRIPTION VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    DUREECONSERVATION INT NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE (IDCATEG ASC),
    INDEX I_FK_LISTEDCPSENSIBLE_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : LISTEMESURESECURITE
CREATE TABLE IF NOT EXISTS LISTEMESURESECURITE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_EST_DE_TYPE_DE_MESURE INT NOT NULL,
    REF INT NOT NULL,
    PRECIS VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_LISTEMESURESECURITE_TYPEMESURESECURITE (ID_EST_DE_TYPE_DE_MESURE ASC),
    INDEX I_FK_LISTEMESURESECURITE_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : ACTEURS
CREATE TABLE IF NOT EXISTS ACTEURS (
    IDACTEUR INT AUTO_INCREMENT NOT NULL,
    IDTYPE INT NOT NULL,
    NOM VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    ADRESSE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CP VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    VILLE VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PAYS VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    TEL VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    MAIL VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (IDACTEUR),
    INDEX I_FK_ACTEURS_TYPEACTEUR (IDTYPE ASC),
    INDEX IDX_ACTEURS_NOM (NOM(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : CATEGDCP
CREATE TABLE IF NOT EXISTS CATEGDCP (
    IDCATEG INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (IDCATEG)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TYPEACTEUR
CREATE TABLE IF NOT EXISTS TYPEACTEUR (
    IDTYPE INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (IDTYPE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TRANSFERTHORSUE
CREATE TABLE IF NOT EXISTS TRANSFERTHORSUE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_TRANSFERT_VERS_PAYS INT NOT NULL,
    REF INT NOT NULL,
    ID_GARANTIE_APPLIQUEE INT NOT NULL,
    DESTINATAIRE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    LIENDOC VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID),
    INDEX I_FK_TRANSFERTHORSUE_PAYS (ID_TRANSFERT_VERS_PAYS ASC),
    INDEX I_FK_TRANSFERTHORSUE_TRAITEMENT (REF ASC),
    INDEX I_FK_TRANSFERTHORSUE_TYPEDEGARANTIE (ID_GARANTIE_APPLIQUEE ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : TRAITEMENT (AVEC OPTIMISATIONS)
CREATE TABLE IF NOT EXISTS TRAITEMENT (
    REF INT AUTO_INCREMENT NOT NULL,
    NOM VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    DATECREATION DATE NULL,
    DATEMAJ DATE NULL,
    TRANSFERTHHORSUE BOOLEAN DEFAULT 0,
    PRIMARY KEY (REF),
    INDEX IDX_TRAITEMENT_NOM (NOM(50)),
    INDEX IDX_TRAITEMENT_DATECREATION (DATECREATION DESC),
    INDEX IDX_TRAITEMENT_DATEMAJ (DATEMAJ DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : CATEGPERSONNECONCERNE
CREATE TABLE IF NOT EXISTS CATEGPERSONNECONCERNE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : CATEGDCPSENSIBLE
CREATE TABLE IF NOT EXISTS CATEGDCPSENSIBLE (
    IDCATEG INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    PRIMARY KEY (IDCATEG)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : UTILISATEURS (AVEC SÉCURITÉ RENFORCÉE)
CREATE TABLE IF NOT EXISTS UTILISATEURS (
    ID INT AUTO_INCREMENT NOT NULL,
    LOGIN VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    MDP VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    DROIT ENUM('US', 'AD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
    DATE_CREATION DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID),
    UNIQUE INDEX IDX_UTILISATEURS_LOGIN (LOGIN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- TABLE : IMPLIQUE_PAR_ACTEUR
CREATE TABLE IF NOT EXISTS IMPLIQUE_PAR_ACTEUR (
    IDACTEUR INT NOT NULL,
    REF INT NOT NULL,
    PRIMARY KEY (IDACTEUR, REF),
    INDEX I_FK_IMPLIQUE_PAR_ACTEUR_ACTEURS (IDACTEUR ASC),
    INDEX I_FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT (REF ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT = '';

-- =====================================================
-- CONTRAINTES DE CLÉS ÉTRANGÈRES AVEC CASCADE
-- =====================================================

ALTER TABLE FINALITE
  ADD CONSTRAINT FK_FINALITE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

ALTER TABLE LISTEDESTINATAIRE
  ADD CONSTRAINT FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE
      FOREIGN KEY (ID_EST_DE_TYPE_DESTINATAIRE) REFERENCES TYPEDESTINATAIRE (ID),
  ADD CONSTRAINT FK_LISTEDESTINATAIRE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

ALTER TABLE LISTEDCP
  ADD CONSTRAINT FK_LISTEDCP_CATEGDCP
      FOREIGN KEY (IDCATEG) REFERENCES CATEGDCP (IDCATEG),
  ADD CONSTRAINT FK_LISTEDCP_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

ALTER TABLE LISTEPERSONNECONCERNE
  ADD CONSTRAINT FK_LISTEPERSONNECONCERNE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE,
  ADD CONSTRAINT FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE
      FOREIGN KEY (ID_EST_DE_CATEGORIE_PERSONNE) REFERENCES CATEGPERSONNECONCERNE (ID);

ALTER TABLE LOG
  ADD CONSTRAINT FK_LOG_UTILISATEURS
      FOREIGN KEY (UTILISATEUR_ID) REFERENCES UTILISATEURS (ID) ON DELETE SET NULL;

ALTER TABLE LISTEDCPSENSIBLE
  ADD CONSTRAINT FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE
      FOREIGN KEY (IDCATEG) REFERENCES CATEGDCPSENSIBLE (IDCATEG),
  ADD CONSTRAINT FK_LISTEDCPSENSIBLE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

ALTER TABLE LISTEMESURESECURITE
  ADD CONSTRAINT FK_LISTEMESURESECURITE_TYPEMESURESECURITE
      FOREIGN KEY (ID_EST_DE_TYPE_DE_MESURE) REFERENCES TYPEMESURESECURITE (ID),
  ADD CONSTRAINT FK_LISTEMESURESECURITE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

ALTER TABLE ACTEURS
  ADD CONSTRAINT FK_ACTEURS_TYPEACTEUR
      FOREIGN KEY (IDTYPE) REFERENCES TYPEACTEUR (IDTYPE);

ALTER TABLE TRANSFERTHORSUE
  ADD CONSTRAINT FK_TRANSFERTHORSUE_PAYS
      FOREIGN KEY (ID_TRANSFERT_VERS_PAYS) REFERENCES PAYS (ID),
  ADD CONSTRAINT FK_TRANSFERTHORSUE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE,
  ADD CONSTRAINT FK_TRANSFERTHORSUE_TYPEDEGARANTIE
      FOREIGN KEY (ID_GARANTIE_APPLIQUEE) REFERENCES TYPEDEGARANTIE (ID);

ALTER TABLE IMPLIQUE_PAR_ACTEUR
  ADD CONSTRAINT FK_IMPLIQUE_PAR_ACTEUR_ACTEURS
      FOREIGN KEY (IDACTEUR) REFERENCES ACTEURS (IDACTEUR) ON DELETE CASCADE,
  ADD CONSTRAINT FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF) ON DELETE CASCADE;

-- =====================================================
-- TRIGGERS POUR JOURNALISATION
-- =====================================================

DELIMITER $$

-- Trigger pour INSERT sur TRAITEMENT
CREATE TRIGGER trg_traitement_insert
AFTER INSERT ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'INSERT',
        CONCAT('Création du traitement ', NEW.REF, ' : ', COALESCE(NEW.NOM, 'Sans nom')),
        NOW()
    );
END$$

-- Trigger pour UPDATE sur TRAITEMENT
CREATE TRIGGER trg_traitement_update
AFTER UPDATE ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'UPDATE',
        CONCAT('Modification du traitement ', NEW.REF),
        NOW()
    );
END$$

-- Trigger pour DELETE sur TRAITEMENT
CREATE TRIGGER trg_traitement_delete
AFTER DELETE ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'DELETE',
        CONCAT('Suppression du traitement ', OLD.REF, ' : ', COALESCE(OLD.NOM, 'Sans nom')),
        NOW()
    );
END$$

-- Triggers pour données sensibles
CREATE TRIGGER trg_sensible_insert
AFTER INSERT ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'INSERT',
        CONCAT('Ajout d\'une donnée sensible au traitement ', NEW.REF),
        NOW()
    );
END$$

CREATE TRIGGER trg_sensible_update
AFTER UPDATE ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'UPDATE',
        CONCAT('Modification d\'une donnée sensible du traitement ', NEW.REF),
        NOW()
    );
END$$

CREATE TRIGGER trg_sensible_delete
AFTER DELETE ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (
        COALESCE(@user_id, NULL),
        'DELETE',
        CONCAT('Suppression d\'une donnée sensible du traitement ', OLD.REF),
        NOW()
    );
END$$

DELIMITER ;

-- =====================================================
-- UTILISATEUR SQL SÉCURISÉ
-- IMPORTANT : À MODIFIER AVANT LE DÉPLOIEMENT
-- =====================================================

-- ATTENTION : Ce script contient des valeurs par défaut à modifier !
-- 1. Remplacer 'CHANGEZ_CE_MOT_DE_PASSE_FORT' par un mot de passe fort
-- 2. Remplacer 'localhost' par l'hôte approprié (ou conserver localhost pour sécurité maximale)
-- 3. Utiliser des variables d'environnement en production

DROP USER IF EXISTS 'userRegistre'@'localhost';
CREATE USER 'userRegistre'@'localhost' IDENTIFIED BY '@dmin123';
GRANT SELECT, INSERT, UPDATE, DELETE ON REGISTRE_DCP.* TO 'userRegistre'@'localhost';
FLUSH PRIVILEGES;

-- =====================================================
-- DONNÉES D'INITIALISATION
-- =====================================================

INSERT INTO CATEGDCP (LIBELLE)
VALUES  ("État civil, identité, données d'identification, images..."),
        ("Vie personnelle (habitude de vie, situation familiale, etc.)"),
        ("Information d'ordre économique et financier (revenus, situation financière, situation fiscale, etc.)"),
        ("Données de connexion (adresse IP, logs, etc.)"),
        ("Données de localisation (déplacement, données GPS, GSM, etc.)"),
        ("Numéro de Sécurité Sociale (ou NIR)");

INSERT INTO CATEGDCPSENSIBLE (LIBELLE)
VALUES  ("Données révélant l'origine raciale ou ethnique"),
        ("Données révélant les opinions politiques"),
        ("Données révélant les convictions religieuse ou philosophiques"),
        ("Données révélant l'appartenance syndicale"),
        ("Données génétique"),
        ("Données biométrique aux fins d'identifier une personne physique de manière unique"),
        ("Données concernant la santé"),
        ("Données concernant la vie sexuelle ou l'orientation sexuelle"),
        ("Données relatives à des condamnations pénales ou infractions");

INSERT INTO CATEGPERSONNECONCERNE (LIBELLE)
VALUES  ("Autres (préciser)"),
        ("Candidats"),
        ("Clients"),
        ("Fournisseurs"),
        ("Prestataires"),
        ("Prospects"),
        ("Salariés"),
        ("Services Internes");

INSERT INTO TYPEACTEUR (LIBELLE)
VALUES  ("Responsable du traitement"),
        ("Délégué à la protection des données"),
        ("Société du DPO (si celui ci est externe)"),
        ("Représentant"),
        ("Responsable(s) conjoint(s)");

INSERT INTO TYPEMESURESECURITE (LIBELLE)
VALUES  ("Autres mesures (à préciser)"),
        ("Chiffrement des données"),
        ("Contrôle d'accès des utilisateurs"),
        ("Contrôle des sous-traitants"),
        ("Mesures de protection des logiciels"),
        ("Mesures de traçabilité"),
        ("Sauvegarde des données");

INSERT INTO TYPEDESTINATAIRE (LIBELLE)
VALUES  ("Destinataires dans des pays tiers ou organisations internationales"),
        ("Partenaires institutionnels ou commerciaux"),
        ("Services interne qui traite les données"),
        ("Sous-traitants");

INSERT INTO TYPEDEGARANTIE (LIBELLE)
VALUES  ("Certification"),
        ("Clause contractuelles types (CCT)"),
        ("Code de conduite"),
        ("Dérogation (art 49)"),
        ("Pays adéquat"),
        ("Privacy shield"),
        ("Règles d'entreprise contraignantes (BCR)");

-- Insertion des pays (liste complète conservée, encodage UTF-8)
INSERT INTO PAYS (NOMPAYS)
VALUES  ("Andorre"), ("Argentine"), ("Canada"), ("États-Unis"), ("Guernesey"),
        ("Île de Man"), ("Îles Féroé"), ("Israël"), ("Jersey"), ("Nouvelle-Zélande"),
        ("Suisse"), ("Uruguay"), ("Afghanistan"), ("Afrique du Sud"), ("Albanie"),
        ("Algérie"), ("Angola"), ("Antigua-et-Barbuda"), ("Arabie saoudite"), ("Arménie"),
        ("Australie"), ("Azerbaïdjan"), ("Bahamas"), ("Bahreïn"), ("Bangladesh"),
        ("Barbade"), ("Belize"), ("Bénin"), ("Bermudes"), ("Bhoutan"),
        ("Biélorussie"), ("Birmanie"), ("Bolivie"), ("Bosnie-Herzégovine"), ("Botswana"),
        ("Brésil"), ("Brunei"), ("Burkina Faso"), ("Burundi"), ("Cambodge"),
        ("Cameroun"), ("Cap-Vert"), ("Chili"), ("Chine"), ("Colombie"),
        ("Comores"), ("Congo"), ("Corée du Nord"), ("Corée du Sud"), ("Costa Rica"),
        ("Côte d'Ivoire"), ("Cuba"), ("Djibouti"), ("Dominique"), ("Égypte"),
        ("Émirats arabes unis"), ("Équateur"), ("Érythrée"), ("Éthiopie"), ("Fidji"),
        ("Gabon"), ("Gambie"), ("Géorgie"), ("Ghana"), ("Gibraltar"),
        ("Grenade"), ("Groenland"), ("Guatemala"), ("Guinée"), ("Guinée équatoriale"),
        ("Guinée-Bissau"), ("Guyana"), ("Haïti"), ("Honduras"), ("Hong-Kong"),
        ("Inde"), ("Indonésie"), ("Iraq"), ("Iran"), ("Jamaïque"),
        ("Japon"), ("Jordanie"), ("Kazakhstan"), ("Kenya"), ("Kirghizstan"),
        ("Kiribati"), ("Kosovo"), ("Koweït"), ("Laos"), ("Lesotho"),
        ("Liban"), ("Liberia"), ("Libye"), ("Macédoine"), ("Madagascar"),
        ("Malaisie"), ("Malawi"), ("Maldives"), ("Mali"), ("Maroc"),
        ("Marshall"), ("Maurice"), ("Mauritanie"), ("Mexique"), ("Micronésie"),
        ("Moldavie"), ("Monaco"), ("Mongolie"), ("Monténégro"), ("Mozambique"),
        ("Namibie"), ("Nauru"), ("Népal"), ("Nicaragua"), ("Niger"),
        ("Nigeria"), ("Oman"), ("Ouganda"), ("Ouzbékistan"), ("Pakistan"),
        ("Palaos"), ("Palestine"), ("Panama"), ("Papouasie-Nouvelle-Guinée"), ("Paraguay"),
        ("Pérou"), ("Philippines"), ("Porto Rico"), ("Qatar"), ("Rép. dém. du Congo"),
        ("République Centrafricaine"), ("République Dominicaine"), ("Russie"), ("Rwanda"), ("Sahara Occidental"),
        ("Saint-Christophe-et-Niévès"), ("Sainte-Lucie"), ("Saint-Marin"), ("Saint-Vincent-et-les Grenadines"), ("Salomon"),
        ("Salvador"), ("Samoa"), ("Sao Tomé-et-Principe"), ("Sénégal"), ("Serbie"),
        ("Seychelles"), ("Sierra Leone"), ("Singapour"), ("Somalie"), ("Soudan"),
        ("Soudan du Sud"), ("Sri Lanka"), ("Suriname"), ("Swaziland"), ("Syrie"),
        ("Tadjikistan"), ("Taiwan"), ("Tanzanie"), ("Tchad"), ("Thaïlande"),
        ("Timor oriental"), ("Togo"), ("Tonga"), ("Trinité-et-Tobago"), ("Tunisie"),
        ("Turkménistan"), ("Turquie"), ("Tuvalu"), ("Ukraine"), ("Vanuatu"),
        ("Venezuela"), ("Viêt Nam"), ("Yémen"), ("Zambie"), ("Zimbabwe");

        

INSERT INTO UTILISATEURS (LOGIN, MDP, DROIT) VALUES
('admin', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'AD');