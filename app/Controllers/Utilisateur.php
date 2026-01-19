<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\Authentif;
use App\Models\ActionsUtilisateur;

/**
 * Contrôleur du module Utilisateur (Visiteur)
 * Gère l'espace personnel des utilisateurs connectés.
 */
class Utilisateur extends BaseController
{
    private $authentif;        // Gestion de l'authentification
    private $idUtilisateur;    // ID de l'utilisateur connecté
    private $data = [];        // Données envoyées aux vues
    private $actUtilisateur;   // Gestion des actions utilisateur

    /**
     * Constructeur CodeIgniter (initController)
     * S'exécute automatiquement avant chaque méthode du contrôleur.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Instanciation du modèle d'authentification
        $this->authentif = new Authentif();

        // Récupération de la session
        $this->session = session();

        // Récupération des infos utilisateur
        $this->idUtilisateur = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        // Instanciation du gestionnaire d'actions utilisateur
        // (tu le créeras plus tard)
        $this->actUtilisateur = new ActionsUtilisateur($this->idUtilisateur);
    }

    /**
     * Page d'accueil de l'utilisateur
     * Affiche la vue principale du visiteur.
     */
    public function index()
    {
        // Envoie la vue d'accueil avec les données utilisateur
        return view('v_visiteurAccueil', $this->data);
    }

    /**
     * Déconnexion de l'utilisateur
     * Appelle la méthode de déconnexion du modèle Authentif.
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }
}