<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Accès SQL brut à la base de données.
 * Toutes les requêtes SQL de l'application passent par ce modèle.
 */
class DataAccess extends Model
{
    protected $db;
    private $userId;

    public function __construct($userId = null)
    {
        parent::__construct();
        $this->db = \Config\Database::connect();

        // On stocke l'utilisateur courant
        $this->userId = $userId;

        // On transmet l'utilisateur à MySQL pour les triggers
        if ($this->userId !== null) {
            $this->db->query("SET @user_id = " . intval($this->userId));
        }
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

    public function getLogs($limit = 50)
    {
        $sql = "SELECT LOG.*, UTILISATEURS.LOGIN
                FROM LOG
                LEFT JOIN UTILISATEURS ON UTILISATEURS.ID = LOG.UTILISATEUR_ID
                ORDER BY DATEMODIFICATION DESC";

        if ($limit !== 'all') {
            $sql .= " LIMIT " . intval($limit);
        }

        return $this->db->query($sql)->getResultArray();
    }

    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        $sql = "SELECT 
                    t.REF,
                    t.NOM,
                    t.DATECREATION,
                    t.DATEMAJ,
                    f.LIBELLE AS FINALITE,

                    CASE 
                        WHEN EXISTS (
                            SELECT 1
                            FROM LISTEDCPSENSIBLE ls
                            WHERE ls.REF = t.REF
                        )
                        THEN 'Oui'
                        ELSE 'Non'
                    END AS DONNEESSENSIBLES,

                    CASE
                        WHEN t.TRANSFERTHHORSUE = 1 THEN 'Oui'
                        ELSE 'Non'
                    END AS TRANSFERT_HORS_UE

                FROM TRAITEMENT t
                LEFT JOIN FINALITE f 
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

    public function enregistrerLog($typeAction, $idUtilisateur, $details)
    {
        $sql = "INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
                VALUES (?, ?, ?, NOW())";

        return $this->db->query($sql, [
            $idUtilisateur,
            $typeAction,
            $details
        ]);
    }

}