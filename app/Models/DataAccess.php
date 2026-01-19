<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Modèle d'accès aux données pour les utilisateurs.
 * Fournit les fonctions nécessaires pour récupérer les informations
 * d'un utilisateur à partir de son login.
 */
class DataAccess extends Model
{
    protected $db;

    /**
     * Constructeur : initialise la connexion à la base de données.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Récupère les informations d'un utilisateur à partir de son login.
     *
     * Cette méthode interroge la table `utilisateurs` et renvoie
     * la première ligne trouvée sous forme de tableau associatif.
     *
     * @param string $login  Identifiant de connexion recherché.
     *
     * @return array|null    Tableau associatif contenant :
     *                       - ID
     *                       - LOGIN
     *                       - MDP
     *                       - DROIT
     *                       Retourne null si aucun utilisateur ne correspond.
     */
    public function getUtilisateur($login)
    {
        $req = "SELECT ID, LOGIN, MDP, DROIT
                FROM utilisateurs
                WHERE LOGIN = ?";

        $rs = $this->db->query($req, [$login]);
        return $rs->getFirstRow('array'); // Renvoie un tableau associatif
    }
}