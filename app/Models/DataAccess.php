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

    public function getAllTraitements()
    {
        return $this->db->query("SELECT * FROM traitement ORDER BY REF DESC")->getResultArray();
    }

    public function getTraitementById($id)
    {
        return $this->db->query("SELECT * FROM traitement WHERE REF = ?", [$id])->getRowArray();
    }

    public function getLogs()
    {
        return $this->db->query("SELECT * FROM log ORDER BY DATEMODIFICATION DESC")->getResultArray();
    }

    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        $sql = "SELECT 
                    t.REF,
                    t.NOM,
                    t.DATECREATION,
                    t.DATEMAJ,
                    t.TRANSFERTHHORSUE,
                    f.LIBELLE AS FINALITE,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1
                            FROM listedcpsensible ls
                            WHERE ls.REF = t.REF
                        )
                        THEN 'Oui'
                        ELSE 'Non'
                    END AS DONNEESSENSIBLES
                FROM traitement t
                LEFT JOIN finalite f 
                    ON f.REF = t.REF AND f.ESTPRINCIPAL = 1
                WHERE 1 = 1";

        if (!empty($search)) {
            $search = strtolower($search);
            $escaped = $this->db->escape('%' . $search . '%');

            $sql .= " AND (
                LOWER(COALESCE(t.NOM, '')) LIKE $escaped
                OR LOWER(COALESCE(t.REF, '')) LIKE $escaped
                OR LOWER(COALESCE(f.LIBELLE, '')) LIKE $escaped
            )";
            log_message('debug', 'Requête SQL : ' . $sql);
        }

        $sql .= " ORDER BY t.REF ASC";

        return $this->db->query($sql)->getResultArray();
    }

}