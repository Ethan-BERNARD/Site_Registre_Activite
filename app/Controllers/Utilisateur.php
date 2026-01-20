<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsUtilisateur;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Contrôleur de l’espace Utilisateur.
 * Gère l’accès aux fonctionnalités destinées aux utilisateurs authentifiés.
 */
class Utilisateur extends BaseController
{
    private $authentif;
    private $idUtilisateur;
    private $data = [];
    private $actUtilisateur;

    /**
     * Initialisation du contrôleur : session + modèles + identité utilisateur.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->authentif = new Authentif();
        $this->session = session();

        $this->idUtilisateur = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actUtilisateur = new ActionsUtilisateur($this->idUtilisateur);
    }

    /**
     * Page d’accueil de l’espace utilisateur.
     */
    public function index()
    {
        return view('v_visiteurAccueil', $this->data);
    }

    /**
     * Déconnexion de l’utilisateur.
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }
}