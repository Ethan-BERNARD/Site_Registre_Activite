<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class JeuTest extends Controller
{
    public function generer()
    {
        $db = Database::connect();

        // Définir l'utilisateur pour les triggers
        $db->query("SET @user_id = 1");

        // ============================================
        // UTILISATEURS
        // ============================================
        // Mot de passe pour tous : "password123"
        $db->query("INSERT INTO UTILISATEURS (LOGIN, MDP, DROIT) VALUES
        ('paul',  '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'US'),
        ('marie', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'US')");

        // ============================================
        // TRAITEMENTS (30 traitements diversifiés)
        // ============================================
        $db->query("INSERT INTO TRAITEMENT (REF, NOM, DATECREATION, DATEMAJ, TRANSFERTHHORSUE) VALUES
        (1, 'Gestion des salariés', '2024-01-10', '2024-01-10', 0),
        (2, 'Suivi des tickets clients', '2024-02-15', '2024-02-15', 0),
        (3, 'Journalisation des accès', '2024-03-01', '2024-03-01', 1),
        (4, 'Campagnes emailing', '2024-03-20', '2024-03-20', 0),
        (5, 'Gestion des badges', '2024-04-05', '2024-04-05', 0),
        (6, 'Gestion des notes de frais', '2024-04-10', '2024-04-10', 0),
        (7, 'Suivi des formations internes', '2024-04-12', '2024-04-12', 0),
        (8, 'Gestion des candidatures', '2024-04-15', '2024-04-15', 0),
        (9, 'Gestion des fournisseurs', '2024-04-18', '2024-04-18', 0),
        (10, 'Suivi des commandes clients', '2024-04-20', '2024-04-20', 0),
        (11, 'Gestion des contrats', '2024-04-22', '2024-04-22', 0),
        (12, 'Archivage comptable', '2024-04-25', '2024-04-25', 0),
        (13, 'Gestion des incidents de sécurité', '2024-04-28', '2024-04-28', 1),
        (14, 'Supervision réseau', '2024-05-01', '2024-05-01', 1),
        (15, 'Gestion des licences logicielles', '2024-05-03', '2024-05-03', 0),
        (16, 'Suivi des accès VPN', '2024-05-05', '2024-05-05', 1),
        (17, 'Gestion des newsletters', '2024-05-07', '2024-05-07', 0),
        (18, 'Analyse de performance commerciale', '2024-05-10', '2024-05-10', 0),
        (19, 'Gestion des stocks', '2024-05-12', '2024-05-12', 0),
        (20, 'Suivi des livraisons', '2024-05-14', '2024-05-14', 0),
        (21, 'Gestion des tickets internes IT', '2024-05-16', '2024-05-16', 0),
        (22, 'Gestion des audits internes', '2024-05-18', '2024-05-18', 0),
        (23, 'Gestion des risques', '2024-05-20', '2024-05-20', 0),
        (24, 'Gestion des plaintes clients', '2024-05-22', '2024-05-22', 0),
        (25, 'Suivi des campagnes publicitaires', '2024-05-24', '2024-05-24', 0),
        (26, 'Gestion des cookies du site web', '2024-05-26', '2024-05-26', 1),
        (27, 'Analyse du trafic web', '2024-05-28', '2024-05-28', 1),
        (28, 'Gestion des demandes RGPD', '2024-05-30', '2024-05-30', 0),
        (29, 'Gestion des attestations fiscales', '2024-06-01', '2024-06-01', 0),
        (30, 'Gestion des assurances', '2024-06-03', '2024-06-03', 0)");

        // ============================================
        // FINALITÉS
        // ============================================
        $db->query("INSERT INTO FINALITE (REF, LIBELLE, ESTPRINCIPAL) VALUES
        (1, 'Gestion administrative du personnel', 1),
        (1, 'Suivi de la paie', 0),
        (2, 'Support client', 1),
        (2, 'Amélioration de la qualité de service', 0),
        (3, 'Sécurité des systèmes d''information', 1),
        (4, 'Promotion des produits et services', 1),
        (4, 'Fidélisation client', 0),
        (5, 'Contrôle d''accès aux locaux', 1),
        (6, 'Gestion des dépenses professionnelles', 1),
        (7, 'Formation continue des salariés', 1),
        (8, 'Recrutement de personnel', 1),
        (9, 'Gestion des achats', 1),
        (10, 'Suivi commercial', 1),
        (11, 'Gestion contractuelle', 1),
        (12, 'Conformité comptable et fiscale', 1),
        (13, 'Détection et réponse aux incidents', 1),
        (14, 'Surveillance de l''infrastructure IT', 1),
        (15, 'Gestion des actifs logiciels', 1),
        (16, 'Sécurisation des accès distants', 1),
        (17, 'Communication marketing', 1),
        (18, 'Analyse des performances', 1),
        (19, 'Gestion des inventaires', 1),
        (20, 'Logistique et transport', 1),
        (21, 'Support informatique interne', 1),
        (22, 'Contrôle interne et conformité', 1),
        (23, 'Maîtrise des risques', 1),
        (24, 'Traitement des réclamations', 1),
        (25, 'Marketing digital', 1),
        (26, 'Analyse du comportement utilisateur', 1),
        (27, 'Statistiques de fréquentation', 1),
        (28, 'Exercice des droits RGPD', 1),
        (29, 'Gestion fiscale', 1),
        (30, 'Gestion des couvertures d''assurance', 1)");

        // ============================================
        // ACTEURS (responsables de traitement, DPO, etc.)
        // ============================================
        $db->query("INSERT INTO ACTEURS (IDTYPE, NOM, ADRESSE, CP, VILLE, PAYS, TEL, MAIL) VALUES
        (1, 'Entreprise ACME SAS', '123 Rue de la République', '75001', 'Paris', 'France', '0123456789', 'contact@acme.fr'),
        (2, 'Jean Dupont - DPO', '123 Rue de la République', '75001', 'Paris', 'France', '0123456790', 'dpo@acme.fr'),
        (3, 'Cabinet DPO Conseil', '45 Avenue des Champs', '75008', 'Paris', 'France', '0123456791', 'contact@dpoconseil.fr'),
        (5, 'Filiale ACME UK Ltd', '10 Downing Street', 'SW1A', 'London', 'Royaume-Uni', '+4412345678', 'uk@acme.com')");

        // ============================================
        // DONNÉES À CARACTÈRE PERSONNEL (DCP)
        // ============================================
        $db->query("INSERT INTO LISTEDCP (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        (1, 1, 'Nom, prénom, date de naissance', 5),
        (1, 3, 'Salaire, primes, avantages', 10),
        (1, 6, 'Numéro de sécurité sociale', 5),
        (2, 1, 'Nom, prénom, email', 3),
        (2, 4, 'Adresse IP, logs de connexion', 1),
        (3, 4, 'Logs systèmes, adresses IP', 1),
        (3, 5, 'Géolocalisation des connexions', 1),
        (4, 1, 'Nom, prénom, email', 3),
        (4, 2, 'Préférences produits', 3),
        (5, 1, 'Photo, nom, prénom', 5),
        (5, 5, 'Badge avec géolocalisation', 3),
        (6, 1, 'Nom, prénom du salarié', 7),
        (6, 3, 'Montants des notes de frais', 7),
        (7, 1, 'Nom, prénom, poste', 5),
        (8, 1, 'Nom, prénom, CV', 2),
        (8, 3, 'Prétentions salariales', 2),
        (9, 1, 'Raison sociale, coordonnées', 10),
        (9, 3, 'Données bancaires fournisseur', 10),
        (10, 1, 'Nom, prénom, adresse client', 10),
        (11, 1, 'Parties contractantes', 10),
        (12, 3, 'Données financières', 10),
        (13, 4, 'Logs des incidents', 3),
        (14, 4, 'Logs réseau, IP', 1),
        (15, 1, 'Utilisateurs licences', 5),
        (16, 4, 'Logs VPN, IP', 2),
        (17, 1, 'Email, nom, prénom', 3),
        (18, 3, 'Données commerciales', 5),
        (19, 1, 'Gestionnaires de stock', 7),
        (20, 1, 'Données de livraison', 2),
        (21, 1, 'Utilisateurs IT', 3),
        (22, 1, 'Auditeurs internes', 10),
        (23, 1, 'Responsables métier', 10),
        (24, 1, 'Clients réclamants', 5),
        (25, 1, 'Profils marketing', 3),
        (26, 4, 'Cookies, IP', 1),
        (27, 4, 'Statistiques anonymisées', 2),
        (28, 1, 'Personnes concernées', 10),
        (29, 3, 'Données fiscales', 10),
        (30, 3, 'Données d''assurance', 10)");

        // ============================================
        // DONNÉES SENSIBLES
        // ============================================
        $db->query("INSERT INTO LISTEDCPSENSIBLE (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        (1, 7, 'Arrêts maladie, certificats médicaux', 5),
        (1, 4, 'Appartenance syndicale', 5),
        (3, 6, 'Empreintes digitales pour accès sécurisés', 3),
        (8, 2, 'Opinions politiques déclarées', 2),
        (13, 9, 'Infractions détectées', 3),
        (24, 7, 'Données de santé dans réclamations', 5)");

        // ============================================
        // PERSONNES CONCERNÉES
        // ============================================
        $db->query("INSERT INTO LISTEPERSONNECONCERNE (REF, ID_EST_DE_CATEGORIE_PERSONNE, PRECIS) VALUES
        (1, 7, 'Tous les salariés de l''entreprise'),
        (2, 3, 'Clients ayant ouvert un ticket'),
        (3, 8, 'Services internes et externes'),
        (4, 6, 'Prospects et clients abonnés'),
        (5, 7, 'Employés avec badge d''accès'),
        (6, 7, 'Salariés en déplacement'),
        (7, 7, 'Participants aux formations'),
        (8, 2, 'Candidats à l''embauche'),
        (9, 4, 'Fournisseurs et prestataires'),
        (10, 3, 'Clients commanditaires'),
        (17, 6, 'Abonnés à la newsletter'),
        (24, 3, 'Clients insatisfaits'),
        (26, 3, 'Visiteurs du site web'),
        (28, 3, 'Toute personne exerçant ses droits')");

        // ============================================
        // DESTINATAIRES DES DONNÉES
        // ============================================
        $db->query("INSERT INTO LISTEDESTINATAIRE (REF, ID_EST_DE_TYPE_DESTINATAIRE, PRECIS) VALUES
        (1, 3, 'Service RH'),
        (1, 3, 'Direction'),
        (1, 4, 'Comptable externe'),
        (2, 3, 'Service support'),
        (3, 3, 'Service IT'),
        (4, 4, 'Plateforme emailing Mailchimp'),
        (5, 3, 'Sécurité des locaux'),
        (9, 4, 'Expert comptable'),
        (10, 3, 'Service commercial'),
        (13, 3, 'RSSI'),
        (14, 4, 'Prestataire de supervision'),
        (17, 4, 'Service emailing'),
        (26, 1, 'Google Analytics (États-Unis)'),
        (27, 1, 'Plateforme analytics (États-Unis)')");

        // ============================================
        // MESURES DE SÉCURITÉ
        // ============================================
        $db->query("INSERT INTO LISTEMESURESECURITE (REF, ID_EST_DE_TYPE_DE_MESURE, PRECIS) VALUES
        (1, 2, 'Chiffrement des données RH au repos'),
        (1, 3, 'Accès restreint au service RH uniquement'),
        (1, 7, 'Sauvegarde quotidienne'),
        (2, 3, 'Authentification requise'),
        (2, 6, 'Logs d''accès conservés 1 an'),
        (3, 2, 'Logs chiffrés'),
        (3, 6, 'Traçabilité complète des accès'),
        (4, 4, 'Contrat de sous-traitance RGPD avec Mailchimp'),
        (5, 6, 'Historique des accès'),
        (13, 3, 'Accès limité au RSSI'),
        (13, 2, 'Données incidents chiffrées'),
        (14, 6, 'Traçabilité des connexions'),
        (16, 2, 'Connexions VPN chiffrées'),
        (26, 1, 'Anonymisation des données après 13 mois'),
        (27, 1, 'Agrégation des données, pas de données individuelles')");

        // ============================================
        // TRANSFERTS HORS UE
        // ============================================
        $db->query("INSERT INTO TRANSFERTHORSUE (REF, ID_TRANSFERT_VERS_PAYS, ID_GARANTIE_APPLIQUEE, DESTINATAIRE, LIENDOC) VALUES
        (3, 4, 2, 'Amazon Web Services (US)', 'https://aws.amazon.com/compliance/gdpr-center/'),
        (13, 4, 2, 'Datadog Inc.', 'https://www.datadoghq.com/legal/privacy/'),
        (14, 4, 2, 'Splunk Inc.', 'https://www.splunk.com/en_us/legal/privacy.html'),
        (16, 4, 2, 'Azure US Datacenter', 'https://azure.microsoft.com/en-us/explore/trusted-cloud/privacy/'),
        (26, 4, 4, 'Google LLC', 'https://policies.google.com/privacy'),
        (27, 4, 2, 'Mixpanel Inc.', 'https://mixpanel.com/legal/privacy-policy/')");

    return "Jeu de test créé avec succès.";
    }
}