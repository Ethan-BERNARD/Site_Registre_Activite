<?php namespace App\Models;

use App\Models\DataAccess;

/**
 * Gère les actions métier disponibles pour un utilisateur standard.
 * Encapsule l'accès aux données et la journalisation des actions utilisateur.
 */
class ActionsUser
{
    /** @var DataAccess Accès aux données */
    private $dao;
    
    /** @var int Identifiant de l'utilisateur courant */
    private $idUser;

    /**
     * Initialise le gestionnaire d'actions pour un utilisateur donné.
     *
     * @param int $idUser Identifiant de l'utilisateur
     */
    public function __construct($idUser)
    {
        $this->idUser = $idUser;
        $this->dao = new DataAccess($idUser);
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
     * Enregistre une action utilisateur dans les logs système.
     *
     * @param string $typeAction Type d'action effectuée (ex: 'CONSULTATION', 'CREATION')
     * @param string $details Description détaillée de l'action
     * @return void
     */
    public function logAction($typeAction, $details)
    {
        $this->dao->enregistrerLog(
            $this->idUser,
            $typeAction,
            $details
        );
    }

    /**
     * Récupère les statistiques pour le tableau de bord utilisateur.
     *
     * @return array Statistiques (nombre total, sensibles, transferts, etc.)
     */
    public function getDashboardStats()
    {
        return $this->dao->getDashboardStats();
    }
}