<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRSSI;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Rssi extends BaseController
{
    private $authentif;
    private $idRssi;
    private $data = [];
    private $actRssi;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->authentif = new Authentif();
        $this->session   = session();

        // 🔐 Vérification de session AVANT d'utiliser les données
        if (!$this->session->get('ID')) {
            redirect()->to('/anonyme')->send();
            exit; // obligatoire pour stopper l'exécution
        }

        // 🔒 Anti-cache
        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");

        // ✔ Maintenant on peut charger les données
        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actRssi = new ActionsRSSI($this->idRssi);
    }

    public function index()
    {
        return view('v_RSSIAccueil', $this->data);
    }

    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    public function tab()
    {
        return view('v_tableau', $this->data);
    }

    public function logs()
    {
        // Exemple : récupération des logs
        // $logs = $this->actRssi->getLogs();

        return view('v_Logs', [
            'identite' => $this->data['identite'],
            'logs' => $logs ?? []
        ]);
    }

}