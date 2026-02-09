<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRssi;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use TCPDF;

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
        $stats = $this->actRssi->getDashboardStats();
        
        return view('rssi/v_rssi_accueil', array_merge($this->data, [
            'stats' => $stats
        ]));
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
        $limit = $this->request->getGet('limit') ?? 50;

        $logs = $this->actRssi->getLogs($limit);

        return view('rssi/v_rssi_logs', [
            'identite' => $this->data['identite'],
            'logs' => $logs,
            'limit' => $limit
        ]);
    }

    public function exportPDF()
    {
        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

        return view('rssi/v_rssi_export_form', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    public function genererPDF()
    {
        $selection = $this->request->getPost('selection');
        
        // Si c'est une sélection JSON (checkboxes)
        if ($selection && $selection !== 'all') {
            $refs = json_decode($selection, true);
            
            if (empty($refs)) {
                return redirect()->back()->with('error', 'Aucun traitement sélectionné.');
            }
            
            $this->logExportSelection($refs);
            
            // Instanciation TCPDF
            $pdf = new TCPDF();
            $pdf->SetCreator('Registre RGPD');
            $pdf->SetAuthor($this->data['identite']);
            $pdf->AddPage('L');
            $pdf->SetTitle('Export sélectif du registre');

            // Récupérer les traitements sélectionnés
            $traitements = [];
            foreach ($refs as $ref) {
                $t = $this->actRssi->getTraitementById($ref);
                if ($t) {
                    $traitements[] = $t;
                }
            }

            // Générer le tableau HTML
            $html = '
            <h1>Export sélectif du registre</h1>

            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 10pt;
                }
                th {
                    background-color: #3b82f6;
                    color: white;
                    font-weight: bold;
                    border: 1px solid #000;
                    padding: 6px;
                    text-align: center;
                }
                td {
                    border: 1px solid #000;
                    padding: 6px;
                }
            </style>

            <table>
                <thead>
                    <tr>
                        <th width="15%">Nom du traitement</th>
                        <th width="7%">N° / Réf</th>
                        <th width="10%">Date de création</th>
                        <th width="10%">Dernière mise à jour</th>
                        <th width="38%">Finalité principale</th>
                        <th width="10%">Transferts hors UE ?</th>
                        <th width="10%">Données sensibles ?</th>
                    </tr>
                </thead>
                <tbody>
            ';

            foreach ($traitements as $t) {
                $html .= '
                    <tr>
                        <td width="15%">' . esc($t['NOM'] ?? 'Non renseigné') . '</td>
                        <td width="7%">' . esc($t['REF'] ?? 'Non renseigné') . '</td>
                        <td width="10%">' . esc($t['DATECREATION'] ?? 'Non renseignée') . '</td>
                        <td width="10%">' . esc($t['DATEMAJ'] ?? 'Non renseignée') . '</td>
                        <td width="38%">' . esc($t['FINALITE'] ?? 'Non renseignée') . '</td>
                        <td width="10%">' . esc($t['TRANSFERT_HORS_UE'] ?? 'Non renseigné') . '</td>
                        <td width="10%">' . esc($t['DONNEESSENSIBLES'] ?? 'Non renseigné') . '</td>
                    </tr>
                ';
            }

            $html .= '
                </tbody>
            </table>
            ';

            $pdf->writeHTML($html, true, false, true, false, '');

            // Nettoyage des buffers
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-Type: application/pdf');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            return $pdf->Output('export_selection.pdf', 'I');
        }
        
    }
    
    private function logExportSelection($refs)
    {
        $count = count($refs);
        $this->actRssi->logAction('EXPORT', "Export PDF de $count traitement(s) : " . implode(', ', $refs));
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

        // --- LOG METIER : consultation d'un traitement ---
        $this->logConsultationTraitement($ref);

        return view('rssi/v_rssi_traitement_detail', [
            'identite'   => $this->data['identite'],
            'traitement' => $traitement
        ]);
    }

    public function searchAjax()
    {
        $q = $this->request->getGet('q');

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($q);

        $html = '';

        foreach ($traitements as $t) {
            // Badge données sensibles
            $badgeSensibles = '';
            if (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui') {
                $badgeSensibles = '<span class="badge badge-oui">Oui</span>';
            } else {
                $badgeSensibles = '<span class="badge badge-non">Non</span>';
            }

            // Badge transfert hors UE
            $badgeTransfert = '';
            if (trim(strtolower($t['TRANSFERT_HORS_UE'])) === 'oui' || $t['TRANSFERT_HORS_UE'] == 1) {
                $badgeTransfert = '<span class="badge badge-oui">Oui</span>';
            } else {
                $badgeTransfert = '<span class="badge badge-non">Non</span>';
            }

            $html .= '
                <tr class="clickable-row">
                    <td onclick="event.stopPropagation();">
                        <input type="checkbox" class="checkbox-traitement" value="' . esc($t['REF']) . '">
                    </td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';"><strong>' . esc($t['NOM']) . '</strong></td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';"><code>' . esc($t['REF']) . '</code></td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';">' . esc($t['DATECREATION']) . '</td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';">' . esc($t['DATEMAJ']) . '</td>
                    <td class="finalite" onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';">' . esc($t['FINALITE']) . '</td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';">' . $badgeSensibles . '</td>
                    <td onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';">' . $badgeTransfert . '</td>
                </tr>
            ';
        }

        return $this->response->setBody($html);
    }

    /* ============================================================
     *  SECTION LOGS METIER (actions non SQL)
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

    private function logExportGlobal()
    {
        $this->actRssi->logAction('EXPORT', 'Export PDF global du registre');
    }

    private function logExportTraitement($idTraitement)
    {
        $this->actRssi->logAction('EXPORT', "Export PDF du traitement $idTraitement");
    }
}