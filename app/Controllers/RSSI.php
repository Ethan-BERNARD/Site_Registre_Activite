<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use \App\Models\Authentif;
use \App\Models\ActionsRSSI; // a creer

/**
 * Contrôleur du module RSSI de l'application
 */
class RSSI extends BaseController {

	private $authentif; // a modif
	private $idComptable; // a modif
	private $data; // a modif
	private $actComptable; // a modif
   
	/**
	 * Constructeur du contrôleur : constructeur fourni par CodeIgniter. S'exécute après le 
	 * constructeur PHP __construct
	 *
	 * Note 1 : Aurait pu être utilisé pour empêcher l'accès aux non-visiteurs mais un constructeur 
	 * 					ne permet pas de renvoyer une vue. Donc pas de vue "erreur" et pas de vue 
	 * 					"connexion" non plus. 
	 * Note 2 : L'interdiction d'accès à ce contrôleur pour les non-visiteurs est opérée par le 
	 * 					biais de "Controller filters" (voir app/Filters/VisiteurFilter.php et 
	 * 					app/Config/Filters.php)
	 */
	public function initController(RequestInterface $request, ResponseInterface $response,
			LoggerInterface $logger ) {
				
		parent::initController($request, $response, $logger);

		// Initialisation des attributs de la classe
		$this->authentif = new Authentif();
		$this->session = session();
		$this->idComptable = $this->session->get('idUser');
		$this->data['identite'] = $this->session->get('prenom').' '.$this->session->get('nom');
        //$this->typeUtil = $this->session->get('idUser');
		$this->actComptable = new ActionsComptable($this->idComptable);

	}

	/**
	 * Méthode par défaut qui renvoie la page d'acceuil 
	 */
	public function index()
	{
		// envoie de la vue accueil du visiteur
		return view('v_comptableAccueil', $this->data); //modif
	}

	/**
	 * Déconnecte la session
	 */
	public function seDeconnecter()	
	{
		return $this->authentif->deconnecter();
	}
	
}