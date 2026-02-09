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
        ('TRT001', 'Gestion des salariés', '2024-01-10', '2024-01-10', 0),
        ('TRT002', 'Suivi des tickets clients', '2024-02-15', '2024-02-15', 0),
        ('TRT003', 'Journalisation des accès', '2024-03-01', '2024-03-01', 1),
        ('TRT004', 'Campagnes emailing', '2024-03-20', '2024-03-20', 0),
        ('TRT005', 'Gestion des badges', '2024-04-05', '2024-04-05', 0)");

        $db->query("INSERT INTO FINALITE (REF, LIBELLE, ESTPRINCIPAL) VALUES
        ('TRT001', 'Gestion RH', 1),
        ('TRT002', 'Suivi des clients', 1),
        ('TRT003', 'Sécurité informatique', 1),
        ('TRT004', 'Marketing', 1),
        ('TRT005', 'Gestion des accès', 1)");

        $db->query("INSERT INTO LISTEDCP (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('TRT001', 1, 'Données RH', '5 ans'),
        ('TRT001', 3, 'Infos financières', '10 ans'),
        ('TRT002', 4, 'Logs clients', '1 an'),
        ('TRT003', 5, 'Logs de sécurité', '1 an'),
        ('TRT004', 2, 'Marketing', '3 ans'),
        ('TRT005', 1, 'Badges employés', '5 ans')");

        $db->query("INSERT INTO LISTEDCPSENSIBLE (REF, IDCATEG, DESCRIPTION, DUREECONSERVATION) VALUES
        ('TRT001', 7, 'Donnée sensible RH', '5 ans'),
        ('TRT003', 6, 'Donnée sensible sécurité', '1 an'),
        ('TRT004', 2, 'Donnée sensible marketing', '3 ans')");

        return "Jeu de test créé avec succès.";
    }
}