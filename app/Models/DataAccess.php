<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Couche d'accès aux données utilisant des requêtes SQL sécurisées.
 * Gère les opérations sur les utilisateurs, traitements et logs.
 */
class DataAccess extends Model
{
    /** @var \CodeIgniter\Database\BaseConnection Connexion à la base de données */
    protected $db;
    
    /** @var int|null Identifiant de l'utilisateur courant */
    private $userId;

    /**
     * Initialise la connexion à la base de données et configure l'utilisateur courant.
     * L'utilisateur est transmis à MySQL pour être utilisé dans les triggers.
     *
     * @param int|null $userId Identifiant de l'utilisateur courant (optionnel)
     */
    public function __construct($userId = null)
    {
        parent::__construct();
        $this->db = \Config\Database::connect();

        $this->userId = $userId;

        if ($this->userId !== null) {
            // Utilisation de requête préparée pour @user_id
            $this->db->query("SET @user_id = ?", [intval($this->userId)]);
        }
    }

    /**
     * Récupère les données complètes d'un utilisateur via son login.
     *
     * @param string $login Login de l'utilisateur
     * @return array|null Données utilisateur (ID, LOGIN, MDP, DROIT) ou null si non trouvé
     */
    public function getUtilisateur(string $login): ?array
    {
        $sql = "SELECT ID, LOGIN, MDP, DROIT
                FROM UTILISATEURS
                WHERE LOGIN = ?";

        return $this->db->query($sql, [$login])->getFirstRow('array');
    }

    /**
     * Récupère uniquement le hash du mot de passe d'un utilisateur.
     * Utile pour les vérifications d'authentification légères.
     *
     * @param string $login Login de l'utilisateur
     * @return string|null Hash du mot de passe ou null si utilisateur non trouvé
     */
    public function getHashUtilisateur(string $login): ?string
    {
        $sql = "SELECT MDP FROM UTILISATEURS WHERE LOGIN = ?";
        $row = $this->db->query($sql, [$login])->getRow();

        return $row ? $row->MDP : null;
    }

    /**
     * Insère un nouvel utilisateur dans la base de données.
     *
     * @param string $login Login du nouvel utilisateur
     * @param string $hash Mot de passe hashé
     * @return bool True si l'insertion a réussi
     */
    public function insertUtilisateur(string $login, string $hash): bool
    {
        $sql = "INSERT INTO UTILISATEURS (LOGIN, MDP) VALUES (?, ?)";
        return $this->db->query($sql, [$login, $hash]);
    }

    /**
     * Récupère tous les traitements du registre, triés par référence décroissante.
     *
     * @return array Liste de tous les traitements
     */
    public function getAllTraitements()
    {
        return $this->db->query("SELECT * FROM TRAITEMENT ORDER BY REF DESC")->getResultArray();
    }

    /**
     * Récupère les détails d'un traitement avec sa finalité principale et ses indicateurs.
     * Calcule automatiquement si le traitement contient des données sensibles.
     *
     * @param int $id Référence du traitement
     * @return array|null Données du traitement enrichies ou null si non trouvé
     */
    public function getTraitementById($id)
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
                WHERE t.REF = ?";

        return $this->db->query($sql, [$id])->getRowArray();
    }

    /**
     * Récupère l'historique des logs système avec les informations utilisateur associées.
     *
     * @param int|string $limit Nombre maximum de logs à retourner ou 'all' pour tous
     * @return array Liste des entrées de log triées par date décroissante
     */
    public function getLogs($limit = 50)
    {
        $sql = "SELECT LOG.*, UTILISATEURS.LOGIN
                FROM LOG
                LEFT JOIN UTILISATEURS ON UTILISATEURS.ID = LOG.UTILISATEUR_ID
                ORDER BY DATEMODIFICATION DESC";

        if ($limit !== 'all') {
            $sql .= " LIMIT ?";
            return $this->db->query($sql, [intval($limit)])->getResultArray();
        }

        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Récupère les traitements avec leur finalité principale et indicateurs.
     * Supporte la recherche par nom, référence ou finalité.
     * 
     * Logique de recherche :
     * - Recherche exacte sur REF si le terme est numérique
     * - Recherche intelligente sur NOM et FINALITE sinon (début de mot ou mot complet)
     *
     * @param string|null $search Terme de recherche optionnel
     * @return array Liste des traitements correspondants, triés par référence
     */
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

        $params = [];

        if (!empty($search)) {
            $search = trim($search);
            
            // CORRECTION SÉCURITÉ : Utilisation de paramètres liés au lieu de concaténation
            if (is_numeric($search)) {
                // Recherche exacte sur REF
                $sql .= " AND t.REF = ?";
                $params[] = intval($search);
            } else {
                // Recherche sur NOM, REF (texte) et FINALITE
                $searchStart = $search . '%';
                $searchWord = '% ' . $search . '%';
                
                $sql .= " AND (
                    LOWER(COALESCE(t.NOM, '')) LIKE LOWER(?)
                    OR LOWER(COALESCE(t.NOM, '')) LIKE LOWER(?)
                    OR LOWER(COALESCE(CAST(t.REF AS CHAR), '')) LIKE LOWER(?)
                    OR LOWER(COALESCE(f.LIBELLE, '')) LIKE LOWER(?)
                    OR LOWER(COALESCE(f.LIBELLE, '')) LIKE LOWER(?)
                )";
                
                $params[] = $searchStart;
                $params[] = $searchWord;
                $params[] = $searchStart;
                $params[] = $searchStart;
                $params[] = $searchWord;
            }
        }

        $sql .= " ORDER BY t.REF ASC";

        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Enregistre une action utilisateur dans les logs système.
     * CORRECTION SÉCURITÉ : Ne log plus la requête SQL complète pour éviter les fuites de données.
     *
     * @param int $idUtilisateur ID de l'utilisateur ayant effectué l'action
     * @param string $typeAction Type d'action (ex: 'CONSULTATION', 'CREATION', 'MODIFICATION')
     * @param string $details Description détaillée de l'action effectuée
     * @return bool True si l'enregistrement a réussi
     */
    public function enregistrerLog($idUtilisateur, $typeAction, $details)
    {
        $sql = "INSERT INTO LOG (UTILISATEUR_ID, TYPEACTION, DETAILS, DATEMODIFICATION)
                VALUES (?, ?, ?, NOW())";

        return $this->db->query($sql, [
            $idUtilisateur,
            $typeAction,
            $details
        ]);
    }

    /**
     * Récupère les statistiques pour le tableau de bord.
     * Calcule les indicateurs clés : nombre total de traitements, traitements sensibles,
     * transferts hors UE et dernière action effectuée.
     *
     * @return array Tableau associatif contenant les statistiques du dashboard
     */
    public function getDashboardStats()
    {
        $stats = [];

        // Total de traitements
        $sql = "SELECT COUNT(*) as total FROM TRAITEMENT";
        $stats['total_traitements'] = $this->db->query($sql)->getRow()->total;

        // Traitements avec données sensibles
        $sql = "SELECT COUNT(DISTINCT t.REF) as total
                FROM TRAITEMENT t
                WHERE EXISTS (
                    SELECT 1 FROM LISTEDCPSENSIBLE ls WHERE ls.REF = t.REF
                )";
        $stats['traitements_sensibles'] = $this->db->query($sql)->getRow()->total;

        // Transferts hors UE
        $sql = "SELECT COUNT(*) as total
                FROM TRAITEMENT
                WHERE TRANSFERTHHORSUE = 1";
        $stats['transferts_hors_ue'] = $this->db->query($sql)->getRow()->total;

        // Dernière action
        $sql = "SELECT TYPEACTION, DETAILS, DATEMODIFICATION, LOGIN
                FROM LOG
                LEFT JOIN UTILISATEURS ON UTILISATEURS.ID = LOG.UTILISATEUR_ID
                ORDER BY DATEMODIFICATION DESC
                LIMIT 1";
        $lastLog = $this->db->query($sql)->getRowArray();
        
        if ($lastLog) {
            $stats['derniere_action'] = [
                'type' => $lastLog['TYPEACTION'],
                'details' => $lastLog['DETAILS'],
                'date' => $lastLog['DATEMODIFICATION'],
                'utilisateur' => $lastLog['LOGIN']
            ];
        } else {
            $stats['derniere_action'] = null;
        }

        return $stats;
    }

    /**
     * NOUVELLE MÉTHODE : Récupère plusieurs traitements par leurs IDs en une seule requête.
     * OPTIMISATION : Évite le problème N+1.
     *
     * @param array $refs Liste des références de traitements
     * @return array Liste des traitements correspondants
     */
    public function getTraitementsByIds(array $refs)
    {
        if (empty($refs)) {
            return [];
        }

        // Sécurité : s'assurer que tous les IDs sont des entiers
        $refs = array_map('intval', $refs);
        
        $placeholders = implode(',', array_fill(0, count($refs), '?'));
        
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
                WHERE t.REF IN ($placeholders)
                ORDER BY t.REF ASC";

        return $this->db->query($sql, $refs)->getResultArray();
    }
}