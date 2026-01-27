<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Accès SQL brut à la base de données.
 * Toutes les requêtes SQL de l'application passent par ce modèle.
 */
class DataAccess extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Retourne les données d'un utilisateur via son login.
     */
    public function getUtilisateur(string $login): ?array
    {
        $sql = "SELECT ID, LOGIN, MDP, DROIT
                FROM utilisateurs
                WHERE LOGIN = ?";

        return $this->db->query($sql, [$login])->getFirstRow('array');
    }

    /**
     * Retourne uniquement le hash du mot de passe d'un utilisateur.
     */
    public function getHashUtilisateur(string $login): ?string
    {
        $sql = "SELECT MDP FROM utilisateur WHERE LOGIN = ?";
        $row = $this->db->query($sql, [$login])->getRow();

        return $row ? $row->MDP : null;
    }

    /**
     * Insère un nouvel utilisateur (login + mot de passe hashé).
     */
    public function insertUtilisateur(string $login, string $hash): bool
    {
        $sql = "INSERT INTO utilisateur (LOGIN, MDP) VALUES (?, ?)";
        return $this->db->query($sql, [$login, $hash]);
    }
}