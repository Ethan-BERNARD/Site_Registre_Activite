DROP DATABASE IF EXISTS REGISTRE_DCP;

CREATE DATABASE IF NOT EXISTS REGISTRE_DCP;
USE REGISTRE_DCP;
# -----------------------------------------------------------------------------
#       TABLE : FINALITE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS FINALITE
 (
   IDFINALITE INT AUTO_INCREMENT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  ,
   ESTPRINCIPAL BOOLEAN  
   , PRIMARY KEY (IDFINALITE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE FINALITE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_FINALITE_TRAITEMENT
     ON FINALITE (REF ASC);

# -----------------------------------------------------------------------------
#       TABLE : TYPEDEGARANTIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEDEGARANTIE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEDESTINATAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEDESTINATAIRE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   ID_EST_DE_TYPE_DESTINATAIRE INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   PRECIS VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LISTEDESTINATAIRE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE
     ON LISTEDESTINATAIRE (ID_EST_DE_TYPE_DESTINATAIRE ASC);

CREATE  INDEX I_FK_LISTEDESTINATAIRE_TRAITEMENT
     ON LISTEDESTINATAIRE (REF ASC);

# -----------------------------------------------------------------------------
#       TABLE : LISTEDCP
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEDCP
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   IDCATEG INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   DESCRIPTION VARCHAR(150) NULL  ,
   DUREECONSERVATION INT NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LISTEDCP
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LISTEDCP_CATEGDCP
     ON LISTEDCP (IDCATEG ASC);

CREATE  INDEX I_FK_LISTEDCP_TRAITEMENT
     ON LISTEDCP (REF ASC);

# -----------------------------------------------------------------------------
#       TABLE : PAYS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PAYS
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   NOMPAYS VARCHAR(100) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEPERSONNECONCERNE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEPERSONNECONCERNE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   ID_EST_DE_CATEGORIE_PERSONNE INT NOT NULL  ,
   PRECIS VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LISTEPERSONNECONCERNE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LISTEPERSONNECONCERNE_TRAITEMENT
     ON LISTEPERSONNECONCERNE (REF ASC);

CREATE  INDEX I_FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE
     ON LISTEPERSONNECONCERNE (ID_EST_DE_CATEGORIE_PERSONNE ASC);

# -----------------------------------------------------------------------------
#       TABLE : LOG
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LOG
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   ID_A_AGIT_SUR INT NOT NULL  ,
   TYPEACTION VARCHAR(150) NULL  ,
   DATEMODIFICATION DATE NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LOG
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LOG_UTILISATEURS
     ON LOG (ID_A_AGIT_SUR ASC);

# -----------------------------------------------------------------------------
#       TABLE : TYPEMESURESECURITE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEMESURESECURITE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TYPEDESTINATAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEDESTINATAIRE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEDCPSENSIBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEDCPSENSIBLE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   IDCATEG INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   DESCRIPTION VARCHAR(150) NULL  ,
   DUREECONSERVATION INT NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LISTEDCPSENSIBLE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE
     ON LISTEDCPSENSIBLE (IDCATEG ASC);

CREATE  INDEX I_FK_LISTEDCPSENSIBLE_TRAITEMENT
     ON LISTEDCPSENSIBLE (REF ASC);

# -----------------------------------------------------------------------------
#       TABLE : LISTEMESURESECURITE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEMESURESECURITE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   ID_EST_DE_TYPE_DE_MESURE INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   PRECIS VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LISTEMESURESECURITE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_LISTEMESURESECURITE_TYPEMESURESECURITE
     ON LISTEMESURESECURITE (ID_EST_DE_TYPE_DE_MESURE ASC);

CREATE  INDEX I_FK_LISTEMESURESECURITE_TRAITEMENT
     ON LISTEMESURESECURITE (REF ASC);

# -----------------------------------------------------------------------------
#       TABLE : ACTEURS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ACTEURS
 (
   IDACTEUR INT AUTO_INCREMENT NOT NULL  ,
   IDTYPE INT NOT NULL  ,
   NOM VARCHAR(40) NULL  ,
   ADRESSE VARCHAR(150) NULL  ,
   CP VARCHAR(10) NULL  ,
   VILLE VARCHAR(100) NULL  ,
   PAYS VARCHAR(100) NULL  ,
   TEL VARCHAR(20) NULL  ,
   MAIL VARCHAR(100) NULL  
   , PRIMARY KEY (IDACTEUR) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE ACTEURS
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_ACTEURS_TYPEACTEUR
     ON ACTEURS (IDTYPE ASC);

# -----------------------------------------------------------------------------
#       TABLE : CATEGDCP
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGDCP
 (
   IDCATEG INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (IDCATEG) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TYPEACTEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEACTEUR
 (
   IDTYPE INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (IDTYPE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TRANSFERTHORSUE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TRANSFERTHORSUE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   ID_TRANSFERT_VERS_PAYS INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   ID_GARANTIE_APPLIQUEE INT NOT NULL  ,
   DESTINATAIRE VARCHAR(150) NULL  ,
   LIENDOC VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TRANSFERTHORSUE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_TRANSFERTHORSUE_PAYS
     ON TRANSFERTHORSUE (ID_TRANSFERT_VERS_PAYS ASC);

CREATE  INDEX I_FK_TRANSFERTHORSUE_TRAITEMENT
     ON TRANSFERTHORSUE (REF ASC);

CREATE  INDEX I_FK_TRANSFERTHORSUE_TYPEDEGARANTIE
     ON TRANSFERTHORSUE (ID_GARANTIE_APPLIQUEE ASC);

# -----------------------------------------------------------------------------
#       TABLE : TRAITEMENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TRAITEMENT
 (
   REF VARCHAR(32) NOT NULL  ,
   NOM VARCHAR(100) NULL  ,
   DATECREATION DATE NULL  ,
   DATEMAJ DATE NULL  ,
   TRANSFERTHHORSUE BOOLEAN  
   , PRIMARY KEY (REF) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGPERSONNECONCERNE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGPERSONNECONCERNE
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGDCPSENSIBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGDCPSENSIBLE
 (
   IDCATEG INT AUTO_INCREMENT NOT NULL  ,
   LIBELLE VARCHAR(150) NULL  
   , PRIMARY KEY (IDCATEG) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : UTILISATEURS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UTILISATEURS
 (
   ID INT AUTO_INCREMENT NOT NULL  ,
   LOGIN VARCHAR(32) NULL  ,
   MDP VARCHAR(150) NULL  ,
   DROIT VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : IMPLIQUE_PAR_ACTEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS IMPLIQUE_PAR_ACTEUR
 (
   IDACTEUR INT AUTO_INCREMENT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  
   , PRIMARY KEY (IDACTEUR,REF) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE IMPLIQUE_PAR_ACTEUR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_IMPLIQUE_PAR_ACTEUR_ACTEURS
     ON IMPLIQUE_PAR_ACTEUR (IDACTEUR ASC);

CREATE  INDEX I_FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT
     ON IMPLIQUE_PAR_ACTEUR (REF ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE FINALITE 
  ADD FOREIGN KEY FK_FINALITE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE LISTEDESTINATAIRE 
  ADD FOREIGN KEY FK_LISTEDESTINATAIRE_TYPEDESTINATAIRE (ID_EST_DE_TYPE_DESTINATAIRE)
      REFERENCES TYPEDESTINATAIRE (ID) ;


ALTER TABLE LISTEDESTINATAIRE 
  ADD FOREIGN KEY FK_LISTEDESTINATAIRE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE LISTEDCP 
  ADD FOREIGN KEY FK_LISTEDCP_CATEGDCP (IDCATEG)
      REFERENCES CATEGDCP (IDCATEG) ;


ALTER TABLE LISTEDCP 
  ADD FOREIGN KEY FK_LISTEDCP_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE LISTEPERSONNECONCERNE 
  ADD FOREIGN KEY FK_LISTEPERSONNECONCERNE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE LISTEPERSONNECONCERNE 
  ADD FOREIGN KEY FK_LISTEPERSONNECONCERNE_CATEGPERSONNECONCERNE (ID_EST_DE_CATEGORIE_PERSONNE)
      REFERENCES CATEGPERSONNECONCERNE (ID) ;


ALTER TABLE LOG 
  ADD FOREIGN KEY FK_LOG_UTILISATEURS (ID_A_AGIT_SUR)
      REFERENCES UTILISATEURS (ID) ;


ALTER TABLE LISTEDCPSENSIBLE 
  ADD FOREIGN KEY FK_LISTEDCPSENSIBLE_CATEGDCPSENSIBLE (IDCATEG)
      REFERENCES CATEGDCPSENSIBLE (IDCATEG) ;


ALTER TABLE LISTEDCPSENSIBLE 
  ADD FOREIGN KEY FK_LISTEDCPSENSIBLE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE LISTEMESURESECURITE 
  ADD FOREIGN KEY FK_LISTEMESURESECURITE_TYPEMESURESECURITE (ID_EST_DE_TYPE_DE_MESURE)
      REFERENCES TYPEMESURESECURITE (ID) ;


ALTER TABLE LISTEMESURESECURITE 
  ADD FOREIGN KEY FK_LISTEMESURESECURITE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE ACTEURS 
  ADD FOREIGN KEY FK_ACTEURS_TYPEACTEUR (IDTYPE)
      REFERENCES TYPEACTEUR (IDTYPE) ;


ALTER TABLE TRANSFERTHORSUE 
  ADD FOREIGN KEY FK_TRANSFERTHORSUE_PAYS (ID_TRANSFERT_VERS_PAYS)
      REFERENCES PAYS (ID) ;


ALTER TABLE TRANSFERTHORSUE 
  ADD FOREIGN KEY FK_TRANSFERTHORSUE_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


ALTER TABLE TRANSFERTHORSUE 
  ADD FOREIGN KEY FK_TRANSFERTHORSUE_TYPEDEGARANTIE (ID_GARANTIE_APPLIQUEE)
      REFERENCES TYPEDEGARANTIE (ID) ;


ALTER TABLE IMPLIQUE_PAR_ACTEUR 
  ADD FOREIGN KEY FK_IMPLIQUE_PAR_ACTEUR_ACTEURS (IDACTEUR)
      REFERENCES ACTEURS (IDACTEUR) ;


ALTER TABLE IMPLIQUE_PAR_ACTEUR 
  ADD FOREIGN KEY FK_IMPLIQUE_PAR_ACTEUR_TRAITEMENT (REF)
      REFERENCES TRAITEMENT (REF) ;


DROP USER IF EXISTS 'userRegistre'@'%';


-- modifier le mot de passe et éventuellement le nom utilisateur
-- pour plus de sécurité remplacer % par adresse ip et créer un user par adresse autorisé a accceder au site
CREATE USER 'userRegistre'@'%' IDENTIFIED BY '@dmin123';


GRANT SELECT, INSERT, UPDATE, DELETE ON REGISTRE_DCP.* TO 'userRegistre'@'%';


FLUSH PRIVILEGES;



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
