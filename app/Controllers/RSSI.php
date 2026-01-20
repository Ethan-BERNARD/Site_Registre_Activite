<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\Authentif;
use App\Models\ActionsRSSI;

/**
 * Contrôleur dédié à l’espace RSSI.
 * 
 * Il gère l’accès aux fonctionnalités du Registre des Activités de Traitement :
 * - consultation et gestion des traitements
 * - contrôle de conformité RGPD
 * - suivi de l’historique
 * - export des fiches PDF
 */
class Rssi extends BaseController {

    /** @var Authentif Gestion de l’authentification et de la session */
    private $authentif;

    /** @var int|null Identifiant du RSSI connecté */
    private $idRssi;

    /** @var array Données envoyées aux vues (identité, etc.) */
    private $data = [];

    /** @var ActionsRSSI Gestion des actions spécifiques au rôle RSSI */
    private $actRssi;
   
    /**
     * Méthode d’initialisation du contrôleur.
     * 
     * Elle est appelée automatiquement par CodeIgniter après le constructeur PHP.
     * 
     * Rôle :
     * - charger la session
     * - récupérer l’identifiant et le login du RSSI connecté
     * - initialiser les modèles nécessaires
     * 
     * ⚠️ L’accès à ce contrôleur est protégé par un filtre (voir app/Filters/RssiFilter.php).
     *    Le filtre empêche les utilisateurs non autorisés d’accéder à cet espace.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Initialisation des services et modèles
        $this->authentif = new Authentif();
        $this->session   = session();

        // Informations du RSSI connecté
        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        // Gestionnaire des actions RSSI
        $this->actRssi = new ActionsRSSI($this->idRssi);
    }

    /**
     * Page d’accueil de l’espace RSSI.
     * 
     * Affiche les informations principales et les accès rapides
     * aux fonctionnalités du registre.
     */
    public function index()
    {
        return view('v_RSSIAccueil', $this->data);
    }

    /**
     * Déconnecte l’utilisateur RSSI et détruit la session.
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }
}