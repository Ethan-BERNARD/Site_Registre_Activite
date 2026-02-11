<?php namespace App\Models;

use App\Models\DataAccess;

/**
 * Gère les actions métier disponibles pour un utilisateur RSSI (administrateur).
 * Encapsule l'accès aux données et la journalisation des actions administrateur.
 */
class ActionsRssi
{
    /** @var DataAccess Accès aux données */
    private $dao;
    
    /** @var int Identifiant du RSSI courant */
    private $idRssi;

    /**
     * Initialise le gestionnaire d'actions pour un RSSI donné.
     *
     * @param int $idRssi Identifiant du RSSI
     */
    public function __construct($idRssi)
    {
        $this->idRssi = $idRssi;
        $this->dao = new DataAccess($idRssi);
    }

    /**
     * Récupère tous les traitements du registre.
     *
     * @return array Liste complète des traitements
     */
    public function getAllTraitements()
    {
        return $this->dao->getAllTraitements();
    }

    /**
     * Récupère les détails complets d'un traitement par son identifiant.
     *
     * @param int $id Référence du traitement
     * @return array|null Données du traitement ou null si non trouvé
     */
    public function getTraitementById($id)
    {
        return $this->dao->getTraitementById($id);
    }

    /**
     * Récupère l'historique des logs système.
     *
     * @param int|string $limit Nombre maximum de logs à retourner ou 'all' pour tous
     * @return array Liste des entrées de log
     */
    public function getLogs($limit = 50)
    {
        return $this->dao->getLogs($limit);
    }

    /**
     * Récupère la liste des traitements avec leur finalité principale et indicateur de données sensibles.
     *
     * @param string|null $search Terme de recherche optionnel (nom, référence, finalité)
     * @return array Liste des traitements correspondants
     */
    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        return $this->dao->getTraitementsAvecFinaliteEtSensibles($search);
    }

    /**
     * Enregistre une action RSSI dans les logs système.
     *
     * @param string $typeAction Type d'action effectuée (ex: 'CONSULTATION', 'MODIFICATION', 'SUPPRESSION')
     * @param string $details Description détaillée de l'action
     * @return void
     */
    public function logAction($typeAction, $details)
    {
        $this->dao->enregistrerLog(
            $this->idRssi,
            $typeAction,
            $details
        );
    }

    /**
     * Récupère les statistiques pour le tableau de bord RSSI.
     *
     * @return array Statistiques (nombre total, sensibles, transferts, dernière action, etc.)
     */
    public function getDashboardStats()
    {
        return $this->dao->getDashboardStats();
    }
}