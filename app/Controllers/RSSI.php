<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRSSI;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Contrôleur de l’espace RSSI.
 * Gère l’accès aux fonctionnalités du registre des traitements.
 */
class Rssi extends BaseController
{
    private $authentif;
    private $idRssi;
    private $data = [];
    private $actRssi;

    /**
     * Initialisation du contrôleur : session + modèles + identité RSSI.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->authentif = new Authentif();
        $this->session   = session();

        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actRssi = new ActionsRSSI($this->idRssi);
    }

    /**
     * Page d’accueil de l’espace RSSI.
     */
    public function index()
    {
        return view('v_RSSIAccueil', $this->data);
    }

    /**
     * Déconnexion du RSSI.
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }
}