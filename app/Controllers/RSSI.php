<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRssi;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Rssi extends BaseController
{
    protected $session;
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

        if (!$this->session->get('ID')) {
            redirect()->to('/anonyme')->send();
            exit;
        }

        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");

        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actRssi = new ActionsRssi($this->idRssi);
    }

    public function index()
    {
        return view('rssi/v_rssi_accueil', $this->data);
    }

    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    public function tab()
    {
        // --- LOG METIER : consultation liste ---
        $this->logConsultationListe();

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

        return view('rssi/v_rssi_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    public function logs()
    {
        $logs = $this->actRssi->getLogs();

        return view('rssi/v_rssi_logs', [
            'identite' => $this->data['identite'],
            'logs' => $logs
        ]);
    }

    public function exportPDF()
    {
        $traitements = $this->actRssi->getAllTraitements();

        return view('rssi/v_rssi_export_form', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    public function genererPDF()
    {
        $idTraitement = $this->request->getPost('idTraitement');

        if ($idTraitement === 'all') {

            // --- LOG METIER : export global ---
            $this->logExportGlobal();

            $traitements = $this->actRssi->getAllTraitements();

            return view('rssi/v_rssi_export_result', [
                'identite' => $this->data['identite'],
                'mode' => 'global',
                'traitements' => $traitements
            ]);
        }

        // --- LOG METIER : export d’un traitement ---
        $this->logExportTraitement($idTraitement);

        $traitement = $this->actRssi->getTraitementById($idTraitement);

        if (!$traitement) {
            return redirect()->back()->with('error', 'Traitement introuvable.');
        }

        return view('rssi/v_rssi_export_result', [
            'identite' => $this->data['identite'],
            'mode' => 'single',
            'traitement' => $traitement
        ]);
    }

    public function tableau()
    {
        $search = $this->request->getGet('search');

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($search);

        return view('rssi/v_rssi_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ], ['saveData' => true]);
    }

    public function detail($ref)
    {
        $traitement = $this->actRssi->getTraitementById($ref);

        if (!$traitement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Traitement introuvable");
        }

        // --- LOG METIER : consultation d’un traitement ---
        $this->logConsultationTraitement($ref);

        return view('rssi/v_rssi_traitement_detail', [
            'identite'   => $this->data['identite'],
            'traitement' => $traitement
        ]);
    }

    public function searchAjax()
    {
        $q = $this->request->getGet('q');

        // --- LOG METIER : recherche ---
        $this->logRecherche($q);

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($q);

        $html = '';

        foreach ($traitements as $t) {
            $html .= '
                <tr onclick="window.location=\'' . site_url('gestionTraitement/detail/' . $t['REF']) . '\';" class="clickable-row">
                    <td>' . esc($t['NOM']) . '</td>
                    <td>' . esc($t['REF']) . '</td>
                    <td>' . esc($t['DATECREATION']) . '</td>
                    <td>' . esc($t['DATEMAJ']) . '</td>
                    <td>' . esc($t['FINALITE']) . '</td>
                    <td>' . esc($t['DONNEESSENSIBLES']) . '</td>
                </tr>
            ';
        }

        return $this->response->setBody($html);
    }

    /* ============================================================
     *  🔥 SECTION LOGS METIER (actions non SQL)
     *  Ces logs restent car les triggers ne couvrent pas ces actions
     * ============================================================ */

    private function logConsultationListe()
    {
        $this->actRssi->logAction('CONSULTATION_LISTE', 'Consultation de la liste des traitements');
    }

    private function logConsultationTraitement($ref)
    {
        $this->actRssi->logAction('CONSULTATION', "Consultation du traitement $ref");
    }

    private function logRecherche($q)
    {
        $this->actRssi->logAction('RECHERCHE', "Recherche AJAX : $q");
    }

    private function logExportGlobal()
    {
        $this->actRssi->logAction('EXPORT', 'Export PDF global du registre');
    }

    private function logExportTraitement($idTraitement)
    {
        $this->actRssi->logAction('EXPORT', "Export PDF du traitement $idTraitement");
    }
}