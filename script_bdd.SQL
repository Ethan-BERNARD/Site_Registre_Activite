DROP DATABASE IF EXISTS REGISTRE_DCP;
CREATE DATABASE IF NOT EXISTS REGISTRE_DCP;
USE REGISTRE_DCP;

-- TABLE : FINALITE
CREATE TABLE IF NOT EXISTS FINALITE (
    IDFINALITE INT AUTO_INCREMENT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    ESTPRINCIPAL BOOLEAN,
    PRIMARY KEY (IDFINALITE)
) COMMENT = '';

CREATE INDEX I_FK_FINALITE_TRAITEMENT
    ON FINALITE (REF ASC);

-- TABLE : TYPEDEGARANTIE
CREATE TABLE IF NOT EXISTS TYPEDEGARANTIE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : LISTEDESTINATAIRE
CREATE TABLE IF NOT EXISTS LISTEDESTINATAIRE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_EST_DE_TYPE_DESTINATAIRE INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    PRECIS VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE
    ON LISTEDESTINATAIRE (ID_EST_DE_TYPE_DESTINATAIRE ASC);

CREATE INDEX I_FK_LISTEDESTINATAIRE_TRAITEMENT
    ON LISTEDESTINATAIRE (REF ASC);

-- TABLE : LISTEDCP
CREATE TABLE IF NOT EXISTS LISTEDCP (
    ID INT AUTO_INCREMENT NOT NULL,
    IDCATEG INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    DESCRIPTION VARCHAR(300) NULL,
    DUREECONSERVATION INT NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LISTEDCP_CATEGDCP
    ON LISTEDCP (IDCATEG ASC);

CREATE INDEX I_FK_LISTEDCP_TRAITEMENT
    ON LISTEDCP (REF ASC);

-- TABLE : PAYS
CREATE TABLE IF NOT EXISTS PAYS (
    ID INT AUTO_INCREMENT NOT NULL,
    NOMPAYS VARCHAR(150) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : LISTEPERSONNECONCERNE
CREATE TABLE IF NOT EXISTS LISTEPERSONNECONCERNE (
    ID INT AUTO_INCREMENT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    ID_EST_DE_CATEGORIE_PERSONNE INT NOT NULL,
    PRECIS VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LISTEPERSONNECONCERNE_TRAITEMENT
    ON LISTEPERSONNECONCERNE (REF ASC);

CREATE INDEX I_FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE
    ON LISTEPERSONNECONCERNE (ID_EST_DE_CATEGORIE_PERSONNE ASC);

-- TABLE : LOG (NOUVELLE STRUCTURE)
CREATE TABLE IF NOT EXISTS LOG (
    ID INT AUTO_INCREMENT NOT NULL,
    UTILISATEUR_ID INT NOT NULL,
    TYPEACTION VARCHAR(300) NOT NULL,
    DETAILS TEXT NOT NULL,
    DATEMODIFICATION DATETIME NOT NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LOG_UTILISATEURS
    ON LOG (UTILISATEUR_ID ASC);

-- TABLE : TYPEMESURESECURITE
CREATE TABLE IF NOT EXISTS TYPEMESURESECURITE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : TYPEDESTINATAIRE
CREATE TABLE IF NOT EXISTS TYPEDESTINATAIRE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : LISTEDCPSENSIBLE
CREATE TABLE IF NOT EXISTS LISTEDCPSENSIBLE (
    ID INT AUTO_INCREMENT NOT NULL,
    IDCATEG INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    DESCRIPTION VARCHAR(300) NULL,
    DUREECONSERVATION INT NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE
    ON LISTEDCPSENSIBLE (IDCATEG ASC);

CREATE INDEX I_FK_LISTEDCPSENSIBLE_TRAITEMENT
    ON LISTEDCPSENSIBLE (REF ASC);

-- TABLE : LISTEMESURESECURITE
CREATE TABLE IF NOT EXISTS LISTEMESURESECURITE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_EST_DE_TYPE_DE_MESURE INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    PRECIS VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_LISTEMESURESECURITE_TYPEMESURESECURITE
    ON LISTEMESURESECURITE (ID_EST_DE_TYPE_DE_MESURE ASC);

CREATE INDEX I_FK_LISTEMESURESECURITE_TRAITEMENT
    ON LISTEMESURESECURITE (REF ASC);

-- TABLE : ACTEURS
CREATE TABLE IF NOT EXISTS ACTEURS (
    IDACTEUR INT AUTO_INCREMENT NOT NULL,
    IDTYPE INT NOT NULL,
    NOM VARCHAR(40) NULL,
    ADRESSE VARCHAR(300) NULL,
    CP VARCHAR(10) NULL,
    VILLE VARCHAR(150) NULL,
    PAYS VARCHAR(150) NULL,
    TEL VARCHAR(20) NULL,
    MAIL VARCHAR(150) NULL,
    PRIMARY KEY (IDACTEUR)
) COMMENT = '';

CREATE INDEX I_FK_ACTEURS_TYPEACTEUR
    ON ACTEURS (IDTYPE ASC);

-- TABLE : CATEGDCP
CREATE TABLE IF NOT EXISTS CATEGDCP (
    IDCATEG INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (IDCATEG)
) COMMENT = '';

-- TABLE : TYPEACTEUR
CREATE TABLE IF NOT EXISTS TYPEACTEUR (
    IDTYPE INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (IDTYPE)
) COMMENT = '';

-- TABLE : TRANSFERTHORSUE
CREATE TABLE IF NOT EXISTS TRANSFERTHORSUE (
    ID INT AUTO_INCREMENT NOT NULL,
    ID_TRANSFERT_VERS_PAYS INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    ID_GARANTIE_APPLIQUEE INT NOT NULL,
    DESTINATAIRE VARCHAR(300) NULL,
    LIENDOC VARCHAR(500) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

CREATE INDEX I_FK_TRANSFERTHORSUE_PAYS
    ON TRANSFERTHORSUE (ID_TRANSFERT_VERS_PAYS ASC);

CREATE INDEX I_FK_TRANSFERTHORSUE_TRAITEMENT
    ON TRANSFERTHORSUE (REF ASC);

CREATE INDEX I_FK_TRANSFERTHORSUE_TYPEDEGARANTIE
    ON TRANSFERTHORSUE (ID_GARANTIE_APPLIQUEE ASC);

-- TABLE : TRAITEMENT
CREATE TABLE IF NOT EXISTS TRAITEMENT (
    REF VARCHAR(32) NOT NULL,
    NOM VARCHAR(150) NULL,
    DATECREATION DATE NULL,
    DATEMAJ DATE NULL,
    TRANSFERTHHORSUE BOOLEAN,
    PRIMARY KEY (REF)
) COMMENT = '';

-- TABLE : CATEGPERSONNECONCERNE
CREATE TABLE IF NOT EXISTS CATEGPERSONNECONCERNE (
    ID INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : CATEGDCPSENSIBLE
CREATE TABLE IF NOT EXISTS CATEGDCPSENSIBLE (
    IDCATEG INT AUTO_INCREMENT NOT NULL,
    LIBELLE VARCHAR(300) NULL,
    PRIMARY KEY (IDCATEG)
) COMMENT = '';

-- TABLE : UTILISATEURS
CREATE TABLE IF NOT EXISTS UTILISATEURS (
    ID INT AUTO_INCREMENT NOT NULL,
    LOGIN VARCHAR(32) NULL,
    MDP VARCHAR(300) NULL,
    DROIT VARCHAR(32) NULL,
    PRIMARY KEY (ID)
) COMMENT = '';

-- TABLE : IMPLIQUE_PAR_ACTEUR
CREATE TABLE IF NOT EXISTS IMPLIQUE_PAR_ACTEUR (
    IDACTEUR INT NOT NULL,
    REF VARCHAR(32) NOT NULL,
    PRIMARY KEY (IDACTEUR, REF)
) COMMENT = '';

CREATE INDEX I_FK_IMPLIQUE_PAR_ACTEUR_ACTEURS
    ON IMPLIQUE_PAR_ACTEUR (IDACTEUR ASC);

CREATE INDEX I_FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT
    ON IMPLIQUE_PAR_ACTEUR (REF ASC);

ALTER TABLE FINALITE
  ADD CONSTRAINT FK_FINALITE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE LISTEDESTINATAIRE
  ADD CONSTRAINT FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE
      FOREIGN KEY (ID_EST_DE_TYPE_DESTINATAIRE) REFERENCES TYPEDESTINATAIRE (ID);

ALTER TABLE LISTEDESTINATAIRE
  ADD CONSTRAINT FK_LISTEDESTINATAIRE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE LISTEDCP
  ADD CONSTRAINT FK_LISTEDCP_CATEGDCP
      FOREIGN KEY (IDCATEG) REFERENCES CATEGDCP (IDCATEG);

ALTER TABLE LISTEDCP
  ADD CONSTRAINT FK_LISTEDCP_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE LISTEPERSONNECONCERNE
  ADD CONSTRAINT FK_LISTEPERSONNECONCERNE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE LISTEPERSONNECONCERNE
  ADD CONSTRAINT FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE
      FOREIGN KEY (ID_EST_DE_CATEGORIE_PERSONNE) REFERENCES CATEGPERSONNECONCERNE (ID);

ALTER TABLE LOG
  ADD CONSTRAINT FK_LOG_UTILISATEURS
      FOREIGN KEY (UTILISATEUR_ID) REFERENCES UTILISATEURS (ID);

ALTER TABLE LISTEDCPSENSIBLE
  ADD CONSTRAINT FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE
      FOREIGN KEY (IDCATEG) REFERENCES CATEGDCPSENSIBLE (IDCATEG);

ALTER TABLE LISTEDCPSENSIBLE
  ADD CONSTRAINT FK_LISTEDCPSENSIBLE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE LISTEMESURESECURITE
  ADD CONSTRAINT FK_LISTEMESURESECURITE_TYPEMESURESECURITE
      FOREIGN KEY (ID_EST_DE_TYPE_DE_MESURE) REFERENCES TYPEMESURESECURITE (ID);

ALTER TABLE LISTEMESURESECURITE
  ADD CONSTRAINT FK_LISTEMESURESECURITE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE ACTEURS
  ADD CONSTRAINT FK_ACTEURS_TYPEACTEUR
      FOREIGN KEY (IDTYPE) REFERENCES TYPEACTEUR (IDTYPE);

ALTER TABLE TRANSFERTHORSUE
  ADD CONSTRAINT FK_TRANSFERTHORSUE_PAYS
      FOREIGN KEY (ID_TRANSFERT_VERS_PAYS) REFERENCES PAYS (ID);

ALTER TABLE TRANSFERTHORSUE
  ADD CONSTRAINT FK_TRANSFERTHORSUE_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

ALTER TABLE TRANSFERTHORSUE
  ADD CONSTRAINT FK_TRANSFERTHORSUE_TYPEDEGARANTIE
      FOREIGN KEY (ID_GARANTIE_APPLIQUEE) REFERENCES TYPEDEGARANTIE (ID);

ALTER TABLE IMPLIQUE_PAR_ACTEUR
  ADD CONSTRAINT FK_IMPLIQUE_PAR_ACTEUR_ACTEURS
      FOREIGN KEY (IDACTEUR) REFERENCES ACTEURS (IDACTEUR);

ALTER TABLE IMPLIQUE_PAR_ACTEUR
  ADD CONSTRAINT FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT
      FOREIGN KEY (REF) REFERENCES TRAITEMENT (REF);

DELIMITER $$

-- TRAITEMENT
CREATE TRIGGER trg_traitement_insert
AFTER INSERT ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'INSERT', CONCAT('Création du traitement ', NEW.REF), NOW());
END$$

CREATE TRIGGER trg_traitement_update
AFTER UPDATE ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'UPDATE', CONCAT('Modification du traitement ', NEW.REF), NOW());
END$$

CREATE TRIGGER trg_traitement_delete
AFTER DELETE ON TRAITEMENT
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'DELETE', CONCAT('Suppression du traitement ', OLD.REF), NOW());
END$$

-- FINALITE
CREATE TRIGGER trg_finalite_insert
AFTER INSERT ON FINALITE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'INSERT',
            CONCAT('Ajout de la finalité "', NEW.LIBELLE, '" au traitement ', NEW.REF),
            NOW());
END$$

CREATE TRIGGER trg_finalite_update
AFTER UPDATE ON FINALITE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'UPDATE',
            CONCAT('Modification de la finalité "', OLD.LIBELLE, '" → "', NEW.LIBELLE,
                   '" pour le traitement ', NEW.REF),
            NOW());
END$$

CREATE TRIGGER trg_finalite_delete
AFTER DELETE ON FINALITE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'DELETE',
            CONCAT('Suppression de la finalité "', OLD.LIBELLE, '" du traitement ', OLD.REF),
            NOW());
END$$

-- LISTEDCPSENSIBLE
CREATE TRIGGER trg_sensible_insert
AFTER INSERT ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'INSERT',
            CONCAT('Ajout d\'une donnée sensible au traitement ', NEW.REF),
            NOW());
END$$

CREATE TRIGGER trg_sensible_update
AFTER UPDATE ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'UPDATE',
            CONCAT('Modification d\'une donnée sensible du traitement ', NEW.REF),
            NOW());
END$$

CREATE TRIGGER trg_sensible_delete
AFTER DELETE ON LISTEDCPSENSIBLE
FOR EACH ROW
BEGIN
    INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
    VALUES (@user_id, 'DELETE',
            CONCAT('Suppression d\'une donnée sensible du traitement ', OLD.REF),
            NOW());
END$$

DELIMITER ;

-- USER SQL
DROP USER IF EXISTS 'userRegistre'@'%';
CREATE USER 'userRegistre'@'%' IDENTIFIED BY 'UN_MDP_A_RENTRER';
GRANT SELECT, INSERT, UPDATE, DELETE ON REGISTRE_DCP.* TO 'userRegistre'@'%';
FLUSH PRIVILEGES;

-- DONNÉES D’INIT (CATEGDCP, CATEGDCPSENSIBLE, CATEGPERSONNECONCERNE, TYPEACTEUR, TYPEMESURESECURITE, TYPEDESTINATAIRE, TYPEDEGARANTIE, PAYS)

INSERT INTO CATEGDCP (LIBELLE)
VALUES  ("Etat civil, identité, données d'identification, images..."),
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
        ("Controle d'accès des utilisateurs"),
        ("Controle des sous-traitants"),
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

INSERT INTO PAYS (NOMPAYS)
VALUES  ("Andorre"),
        ("Argentine"),
        ("Canada"),
        ("Etats-Unis"),
        ("Guernesey"),
        ("Ile de Man"),
        ("Iles FEROE"),
        ("Israël"),
        ("Jersey"),
        ("Nouvelle-Zélande"),
        ("Suisse"),
        ("Uruguay"),
        ("Afghanistan"),
        ("Afrique du Sud"),
        ("Albanie"),
        ("Algérie"),
        ("Angola"),
        ("Antigua-et-Barbuda"),
        ("Arabie saoudite"),
        ("Arménie"),
        ("Australie"),
        ("Azerbaïdjan"),
        ("Bahamas"),
        ("Bahreïn"),
        ("Bangladesh"),
        ("Barbade"),
        ("Belize"),
        ("Bénin"),
        ("Bermudes"),
        ("Bhoutan"),
        ("Biélorussie"),
        ("Birmanie"),
        ("Bolivie"),
        ("Bosnie-Herzégovine"),
        ("Botswana"),
        ("Brésil"),
        ("Brunei"),
        ("Burkina Faso"),
        ("Burundi"),
        ("Cambodge"),
        ("Cameroun"),
        ("Cap-Vert"),
        ("Chili"),
        ("Chine"),
        ("Colombie"),
        ("Comores"),
        ("Congo"),
        ("Corée du Nord"),
        ("Corée du Sud"),
        ("Costa Rica"),
        ("Côte d'Ivoire"),
        ("Cuba"),
        ("Djibouti"),
        ("Dominique"),
        ("Egypte"),
        ("Emirats arabes unis"),
        ("Equateur"),
        ("Erythrée"),
        ("Éthiopie"),
        ("Fidji"),
        ("Gabon"),
        ("Gambie"),
        ("Géorgie"),
        ("Ghana"),
        ("Gibraltar"),
        ("Grenade"),
        ("Groenland"),
        ("Guatemala"),
        ("Guinée"),
        ("Guinée équatoriale"),
        ("Guinée-Bissau"),
        ("Guyana"),
        ("Haïti"),
        ("Honduras"),
        ("Hong-Kong"),
        ("Inde"),
        ("Indonésie"),
        ("Iraq"),
        ("Iran"),
        ("Jamaïque"),
        ("Japon"),
        ("Jordanie"),
        ("Kazakhstan"),
        ("Kenya"),
        ("Kirghizstan"),
        ("Kiribati"),
        ("Kosovo"),
        ("Koweït"),
        ("Laos"),
        ("Lesotho"),
        ("Liban"),
        ("Liberia"),
        ("Libye"),
        ("Macédoine"),
        ("Madagascar"),
        ("Malaisie"),
        ("Malawi"),
        ("Maldives"),
        ("Mali"),
        ("Maroc"),
        ("Marshall"),
        ("Maurice"),
        ("Mauritanie"),
        ("Mexique"),
        ("Micronésie"),
        ("Moldavie"),
        ("Monaco"),
        ("Mongolie"),
        ("Monténégro"),
        ("Mozambique"),
        ("Namibie"),
        ("Nauru"),
        ("Népal"),
        ("Nicaragua"),
        ("Niger"),
        ("Nigeria"),
        ("Oman"),
        ("Ouganda"),
        ("Ouzbékistan"),
        ("Pakistan"),
        ("Palaos"),
        ("Palestine"),
        ("Panama"),
        ("Papouasie-Nouvelle-Guinée"),
        ("Paraguay"),
        ("Pérou"),
        ("Philippines"),
        ("Porto Rico"),
        ("Qatar"),
        ("Rép. dém. du Congo"),
        ("République Centrafricaine"),
        ("République Dominicaine"),
        ("Russie"),
        ("Rwanda"),
        ("Sahara Occidental"),
        ("Saint-Christophe-et-Niévès"),
        ("Sainte-Lucie"),
        ("Saint-Marin"),
        ("Saint-Vincent-et-les Grenadines"),
        ("Salomon"),
        ("Salvador"),
        ("Samoa"),
        ("Sao Tomé-et-Principe"),
        ("Sénégal"),
        ("Serbie"),
        ("Seychelles"),
        ("Sierra Leone"),
        ("Singapour"),
        ("Somalie"),
        ("Soudan"),
        ("Soudan du Sud"),
        ("Sri Lanka"),
        ("Suriname"),
        ("Swaziland"),
        ("Syrie"),
        ("Tadjikistan"),
        ("Taiwan"),
        ("Tanzanie"),
        ("Tchad"),
        ("Thaïlande"),
        ("Timor oriental"),
        ("Togo"),
        ("Tonga"),
        ("Trinité-et-Tobago"),
        ("Tunisie"),
        ("Turkménistan"),
        ("Turquie"),
        ("Tuvalu"),
        ("Ukraine"),
        ("Vanuatu"),
        ("Venezuela"),
        ("Viêt Nam"),
        ("Yémen"),
        ("Zambie"),
        ("Zimbabwe");
