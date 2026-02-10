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

        // Exécuter chaque requête séparément
        $db->query("INSERT INTO UTILISATEURS (LOGIN, MDP, DROIT) VALUES
        ('admin', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'AD'),
        ('paul',  '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'US'),
        ('marie', '\$2y\$10\$LQxaBBFyjfFbNT3D4CP2iuGjr0xMlUVZVfUNUdixx9AuuaEXDmb9i', 'US')");
        
        $db->query("INSERT INTO TRAITEMENT (REF, NOM, DATECREATION, DATEMAJ, TRANSFERTHHORSUE) VALUES
        ('1', 'Gestion des salariés', '2024-01-10', '2024-01-10', 0),
        ('2', 'Suivi des tickets clients', '2024-02-15', '2024-02-15', 0),
        ('3', 'Journalisation des accès', '2024-03-01', '2024-03-01', 1),
        ('4', 'Campagnes emailing', '2024-03-20', '2024-03-20', 0),
        ('5', 'Gestion des badges', '2024-04-05', '2024-04-05', 0),

        -- AJOUT MASSIF DE TRAITEMENTS
        ('6', 'Gestion des notes de frais', '2024-04-10', '2024-04-10', 0),
        ('7', 'Suivi des formations internes', '2024-04-12', '2024-04-12', 0),
        ('8', 'Gestion des candidatures', '2024-04-15', '2024-04-15', 0),
        ('9', 'Gestion des fournisseurs', '2024-04-18', '2024-04-18', 0),
        ('10', 'Suivi des commandes clients', '2024-04-20', '2024-04-20', 0),
        ('11', 'Gestion des contrats', '2024-04-22', '2024-04-22', 0),
        ('12', 'Archivage comptable', '2024-04-25', '2024-04-25', 0),
        ('13', 'Gestion des incidents de sécurité', '2024-04-28', '2024-04-28', 1),
        ('14', 'Supervision réseau', '2024-05-01', '2024-05-01', 1),
        ('15', 'Gestion des licences logicielles', '2024-05-03', '2024-05-03', 0),
        ('16', 'Suivi des accès VPN', '2024-05-05', '2024-05-05', 1),
        ('17', 'Gestion des newsletters', '2024-05-07', '2024-05-07', 0),
        ('18', 'Analyse de performance commerciale', '2024-05-10', '2024-05-10', 0),
        ('19', 'Gestion des stocks', '2024-05-12', '2024-05-12', 0),
        ('20', 'Suivi des livraisons', '2024-05-14', '2024-05-14', 0),
        ('21', 'Gestion des tickets internes IT', '2024-05-16', '2024-05-16', 0),
        ('22', 'Gestion des audits internes', '2024-05-18', '2024-05-18', 0),
        ('23', 'Gestion des risques', '2024-05-20', '2024-05-20', 0),
        ('24', 'Gestion des plaintes clients', '2024-05-22', '2024-05-22', 0),
        ('25', 'Suivi des campagnes publicitaires', '2024-05-24', '2024-05-24', 0),
        ('26', 'Gestion des cookies du site web', '2024-05-26', '2024-05-26', 1),
        ('27', 'Analyse du trafic web', '2024-05-28', '2024-05-28', 1),
        ('28', 'Gestion des demandes RGPD', '2024-05-30', '2024-05-30', 0),
        ('29', 'Gestion des attestations fiscales', '2024-06-01', '2024-06-01', 0),
        ('30', 'Gestion des assurances', '2024-06-03', '2024-06-03', 0)");

        $db->query("INSERT INTO FINALITE (REF, LIBELLE, ESTPRINCIPAL) VALUES
        ('1', 'Gestion RH', 1),
        ('2', 'Suivi des clients', 1),
        ('3', 'Sécurité informatique', 1),
        ('4', 'Marketing', 1),
        ('5', 'Gestion des accès', 1)");

        $db->query("INSERT INTO LISTEDCP (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('1', 1, 'Données RH', '5 ans'),
        ('1', 3, 'Infos financières', '10 ans'),
        ('2', 4, 'Logs clients', '1 an'),
        ('3', 5, 'Logs de sécurité', '1 an'),
        ('4', 2, 'Marketing', '3 ans'),
        ('5', 1, 'Badges employés', '5 ans')");

        $db->query("INSERT INTO LISTEDCPSENSIBLE (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('1', 7, 'Donnée sensible RH', '5 ans'),
        ('3', 6, 'Donnée sensible sécurité', '1 an'),
        ('4', 2, 'Donnée sensible marketing', '3 ans')");

        return "Jeu de test créé avec succès.";
    }
}