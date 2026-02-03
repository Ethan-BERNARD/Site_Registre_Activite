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
                FROM UTILISATEURS
                WHERE LOGIN = ?";

        return $this->db->query($sql, [$login])->getFirstRow('array');
    }

    /**
     * Retourne uniquement le hash du mot de passe d'un utilisateur.
     */
    public function getHashUtilisateur(string $login): ?string
    {
        $sql = "SELECT MDP FROM UTILISATEURS WHERE LOGIN = ?";
        $row = $this->db->query($sql, [$login])->getRow();

        return $row ? $row->MDP : null;
    }

    /**
     * Insère un nouvel utilisateur (login + mot de passe hashé).
     */
    public function insertUtilisateur(string $login, string $hash): bool
    {
        $sql = "INSERT INTO UTILISATEURS (LOGIN, MDP) VALUES (?, ?)";
        return $this->db->query($sql, [$login, $hash]);
    }

    public function getAllTraitements()
    {
        return $this->db->query("SELECT * FROM TRAITEMENT ORDER BY REF DESC")->getResultArray();
    }

    public function getTraitementById($id)
    {
        return $this->db->query("SELECT * FROM TRAITEMENT WHERE REF = ?", [$id])->getRowArray();
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
                            FROM LISTEDCPSENSIBLE ls
                            WHERE ls.REF = t.REF
                        )
                        THEN 'Oui'
                        ELSE 'Non'
                    END AS DONNEESSENSIBLES
                FROM TRAITEMENT t
                LEFT JOIN FINALITE f
                    ON f.REF = t.REF AND f.ESTPRINCIPAL = 1
                WHERE 1 = 1";

        if (!empty($search)) {
            $sql .= " AND LOWER(t.NOM) LIKE " . $this->db->escape('%' . strtolower($search) . '%');
        }

        $sql .= " ORDER BY t.REF ASC";

        return $this->db->query($sql)->getResultArray();
    }

}