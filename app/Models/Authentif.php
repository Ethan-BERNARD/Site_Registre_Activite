<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;

class Authentif extends Model
{
	private $session;
	
    function __construct()
    {
      parent::__construct();
			$this->session = session();
    }

	public function estComptable()
	{
		if (!is_null($this->session->get('idUser'))) {
			return $this->session->get('typeUtil') === "comptable";
		}
		return false;
	}

	public function estVisiteur()
	{
		if (!is_null($this->session->get('idUser'))) {
			return $this->session->get('typeUtil') === "visiteur";
		}
		return false;
	}
	
	/**
	 * Enregistre dans une variable de session les infos de l'utilisateur connecté
	 * 
	 * @param $authUser tableau assocatif contenant les caractéristiques de l'utilisateur à enregistrer
	 */
	public function connecter($authUser)
	{ // TODO : Lorsqu'il y aura d'autres profils d'utilisateurs (comptables, etc.)
	  // il faudra ajouter cette information de profil dans la session MODIF
		$this->session->set('idUser', $authUser['id']);
		$this->session->set('nom', $authUser['nom']);
		$this->session->set('prenom', $authUser['prenom']);
		$this->session->set('login', $authUser['login']);
		$this->session->set('typeUtil', $authUser['typeUtil']);
	}

	/**
	 * Détruit la session active et redirige vers le contrôleur par défaut
	 */
	public function deconnecter()
	{
		$authUser = array('idUser', 'nom', 'prenom', 'login');
		$this->session->remove($authUser);
		$this->session->destroy();

		return redirect()->to('/anonyme');
	}

	/**
	 * Vérifie en base de données si les informations de connexions sont correctes
	 * 
	 * @return : renvoie l'id, le nom et le prenom de l'utilisateur dans un tableau s'il est reconnu, sinon un tableau vide.
	 */
	public function authentifier ($login, $mdp) 
	{
		$dao = new DataAccess();
		$authUser = $dao->getUtilisateur($login);
		
		if (empty($authUser) or ($authUser['mdp'] != $mdp)) {
			$authUser = null;
		}
		else {
			$authUser['mdp'] ='';
		}

		return $authUser;
	}
}