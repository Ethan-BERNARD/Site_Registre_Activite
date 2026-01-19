<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;
use \DateTime;
use \DateInterval;

/**
 * Modèle représentant tous les traitements possibles attachés à un Visiteur désigné
 *
 */
class ActionsUtilisateur extends Model {

	private $dao;
	private $idUtilisateur;
	 
	function __construct($idUtilisateur)
	{
		// Call the Model constructor
		parent::__construct();

		// chargement du modèle d'accès aux données qui est utile à toutes les méthodes
		$this->dao = new DataAccess();
		$this->idUtilisateur = $idUtilisateur;
	}

	
	/**
	 * Liste les fiches existantes d'un utilisateur 
	 *
	 * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
	*/
	public function getLesFichesDuUtilisateur($message=null)
	{		
		return $this->dao->getLesFiches($this->idUtilisateur);
	}	
}