<?php namespace App\Controllers;

use CodeIgniter\Controller;
use \App\Models\Authentif;

/**
 * Accès à l'application par défaut pour tout utilisateur non authentifié.
 * Gestion du formulaire de connexion et sa soumission
 */
class Anonyme extends BaseController
{
	/**
	 * Détecte si l'utilisateur est authentifié et envoie la view adaptée à son profil ou 
	 * le formulaire de connexion s'il n'est pas authentifié.
	 */
	public function index()
	{
		$authentif = new Authentif();

		if ($authentif->estVisiteur() === true) {
			return redirect()->to('/visiteur');
		} 
		elseif ($authentif->estComptable() === true) {
			return redirect()->to('/comptable');
		} 
		else {
			return $this->login();
		}
	}

	/**
	 * Envoi du formulaire de connexion, lequel peut incorporer un message d'erreur
	 *
	 */
	public function login($errMsg = null)
	{
		$data = array('erreur'=>$errMsg);
		return view('v_connexion', $data);
	}

	/**
	 * Traite la soumission du formulaire de connexion afin d'authentifier l'utilisateur
	 * 
	 */
	public function seConnecter () 
	{	// TODO : conrôler que l'obtention des données postées ne rend pas d'erreurs 

		$login = $this->request->getPost('login');
		$mdp = $this->request->getPost('mdp');
		
		$authentif = new Authentif();
		$authUser = $authentif->authentifier($login, $mdp);

		if(empty($authUser))
		{
			return $this->login ('Login ou mot de passe incorrect');
		}
		else
		{
			$authentif->connecter($authUser);
			return $this->index();
		}
	}
}