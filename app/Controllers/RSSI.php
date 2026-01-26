<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRssi;
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

        // Vérification de session
        if (!$this->session->get('ID')) {
            redirect()->to('/anonyme')->send();
            exit;
        }

        // Anti-cache
        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");

        // Identité
        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        // Modèle métier (aucun SQL ici)
        $this->actRssi = new ActionsRssi($this->idRssi);
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
        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

        return view('v_tableau', [
            'identite'    => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    public function logs()
    {
        // Récupération via logique métier (pas de SQL ici)
        $logs = $this->actRssi->getLogs();

        return view('v_Logs', ['identite' => $this->data['identite'],'logs' => $logs]);
    }

    public function exportPDF()
    {
        $traitements = $this->actRssi->getAllTraitements();

        return view('v_ExportPDF', [
            'identite'    => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    public function genererPDF()
    {
        $idTraitement = $this->request->getPost('idTraitement');

        if ($idTraitement === 'all') {
            $traitements = $this->actRssi->getAllTraitements();

            return view('v_ExportPDF_Result', [
                'identite'    => $this->data['identite'],
                'mode'        => 'global',
                'traitements' => $traitements
            ]);
        }

        $traitement = $this->actRssi->getTraitementById($idTraitement);

        if (!$traitement) {
            return redirect()->back()->with('error', 'Traitement introuvable.');
        }

        return view('v_ExportPDF_Result', [
            'identite'   => $this->data['identite'],
            'mode'       => 'single',
            'traitement' => $traitement
        ]);
    }

    public function tableau()
    {
        $search = $this->request->getGet('search');

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($search);

        return view('v_tableau', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ], ['saveData' => true]);
    }
}