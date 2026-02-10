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
        ('5', 'Gestion des badges', '2024-04-05', '2024-04-05', 0)");

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