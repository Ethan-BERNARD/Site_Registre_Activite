<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;
use \DateTime;
use \DateInterval;

/**
 * Modèle représentant tous les traitements possibles attachés à un Rssi désigné
 */
class ActionsRssi extends Model {
	private $dao;
	private $idRssi;
	 
	function __construct($idRssi)
	{
		parent::__construct();

		$this->dao = new DataAccess();
		$this->idRssi = $idRssi;
	}	

	/**
	 * Liste les fiches existantes des visiteurs
	 *
	 * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
	*/
	public function getLesFichesUtilisateur($message=null)
	{		
		return $this->dao->getLesFiches($this->idRssi);
	}
}