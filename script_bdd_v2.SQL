DROP DATABASE IF EXISTS REGISTRE_DCP;

CREATE DATABASE IF NOT EXISTS REGISTRE_DCP;
USE REGISTRE_DCP;
# -----------------------------------------------------------------------------
#       TABLE : FINALITE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS FINALITE
 (
   IDFINALITE INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  ,
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
   ID INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEDESTINATAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEDESTINATAIRE
 (
   ID INT NOT NULL  ,
   ID_EST_DE_TYPE_DESTINATAIRE INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   PRECIS VARCHAR(32) NULL  
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
   ID INT NOT NULL  ,
   IDCATEG INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   DESCRIPTION VARCHAR(32) NULL  ,
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
   ID INT NOT NULL  ,
   NOMPAYS VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEPERSONNECONCERNE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEPERSONNECONCERNE
 (
   ID INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   ID_EST_DE_CATEGORIE_PERSONNE INT NOT NULL  ,
   PRECIS VARCHAR(32) NULL  
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
   ID INT NOT NULL  ,
   ID_A_AGIT_SUR INT NOT NULL  ,
   TYPEACTION VARCHAR(32) NULL  ,
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
   ID INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TYPEDESTINATAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEDESTINATAIRE
 (
   ID INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : LISTEDCPSENSIBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LISTEDCPSENSIBLE
 (
   ID INT NOT NULL  ,
   IDCATEG INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   DESCRIPTION VARCHAR(32) NULL  ,
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
   ID INT NOT NULL  ,
   ID_EST_DE_TYPE_DE_MESURE INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   PRECIS VARCHAR(32) NULL  
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
   IDACTEUR INT NOT NULL  ,
   IDTYPE INT NOT NULL  ,
   NOM VARCHAR(32) NULL  ,
   ADRESSE VARCHAR(32) NULL  ,
   CP VARCHAR(32) NULL  ,
   VILLE VARCHAR(32) NULL  ,
   PAYS VARCHAR(32) NULL  ,
   TEL VARCHAR(32) NULL  ,
   MAIL VARCHAR(32) NULL  
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
   IDCATEG INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (IDCATEG) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TYPEACTEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPEACTEUR
 (
   IDTYPE INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (IDTYPE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TRANSFERTHORSUE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TRANSFERTHORSUE
 (
   ID INT NOT NULL  ,
   ID_TRANSFERT_VERS_PAYS INT NOT NULL  ,
   REF VARCHAR(32) NOT NULL  ,
   ID_GARANTIE_APPLIQUEE INT NOT NULL  ,
   DESTINATAIRE VARCHAR(32) NULL  ,
   LIENDOC VARCHAR(32) NULL  
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
   NOM VARCHAR(32) NULL  ,
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
   ID INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGDCPSENSIBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGDCPSENSIBLE
 (
   IDCATEG INT NOT NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (IDCATEG) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : UTILISATEURS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UTILISATEURS
 (
   ID INT NOT NULL  ,
   LOGIN VARCHAR(32) NULL  ,
   MDP VARCHAR(32) NULL  ,
   DROIT VARCHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : IMPLIQUE_PAR_ACTEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS IMPLIQUE_PAR_ACTEUR
 (
   IDACTEUR INT NOT NULL  ,
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