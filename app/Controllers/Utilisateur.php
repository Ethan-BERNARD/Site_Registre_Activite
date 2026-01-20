<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\Authentif;
use App\Models\ActionsUtilisateur;

/**
 * Contrôleur dédié à l’espace Utilisateur.
 *
 * Il gère l’accès aux fonctionnalités destinées aux utilisateurs standard :
 * - consultation des documents internes
 * - lecture des communications du RSSI
 * - gestion d’informations personnelles
 * - accès aux ressources de l’intranet
 *
 * L’accès à ce contrôleur est protégé par un filtre (voir app/Filters/UtilisateurFilter.php),
 * garantissant que seuls les utilisateurs authentifiés peuvent y accéder.
 */
class Utilisateur extends BaseController
{
    /** @var Authentif Gestion de l’authentification et de la session */
    private $authentif;

    /** @var int|null Identifiant de l’utilisateur connecté */
    private $idUtilisateur;

    /** @var array Données envoyées aux vues (identité, etc.) */
    private $data = [];

    /** @var ActionsUtilisateur Gestion des actions propres au rôle utilisateur */
    private $actUtilisateur;

    /**
     * Méthode d’initialisation du contrôleur.
     *
     * Appelée automatiquement par CodeIgniter avant chaque méthode publique.
     * Elle initialise :
     * - la session
     * - l’identifiant et le login de l’utilisateur connecté
     * - les modèles nécessaires au fonctionnement du module utilisateur
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Gestion de l’authentification
        $this->authentif = new Authentif();

        // Session active
        $this->session = session();

        // Informations de l’utilisateur connecté
        $this->idUtilisateur = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        // Gestionnaire des actions utilisateur
        $this->actUtilisateur = new ActionsUtilisateur($this->idUtilisateur);
    }

    /**
     * Page d’accueil de l’espace utilisateur.
     *
     * Affiche la vue principale contenant les informations générales
     * et les accès rapides aux fonctionnalités de l’intranet.
     */
    public function index()
    {
        return view('v_visiteurAccueil', $this->data);
    }

    /**
     * Déconnecte l’utilisateur et détruit la session.
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }
}