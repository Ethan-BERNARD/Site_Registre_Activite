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
        $idTraitement = $this->request->getPost('idTraitement');

        // Instanciation TCPDF
        $pdf = new TCPDF();
        $pdf->SetCreator('Registre RGPD');
        $pdf->SetAuthor($this->data['identite']);
        $pdf->AddPage('L');

        /* ============================================================
        *  EXPORT GLOBAL
        * ============================================================ */
        if ($idTraitement === 'all') {

            $this->logExportGlobal();
            $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

            $pdf->SetTitle('Export global du registre');

            // Style + tableau
            $html = '
            <h1>Export global du registre</h1>

            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 10pt;
                }
                th {
                    background-color: #f2f2f2;
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

            // Nettoyage complet des buffers AVANT la sortie PDF
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Headers PDF
            header('Content-Type: application/pdf');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            return $pdf->Output('export_global.pdf', 'I');
        }

        /* ============================================================
        *  EXPORT D'UN TRAITEMENT UNIQUE
        * ============================================================ */
        $this->logExportTraitement($idTraitement);
        $traitement = $this->actRssi->getTraitementById($idTraitement);

        if (!$traitement) {
            return redirect()->back()->with('error', 'Traitement introuvable.');
        }

        $pdf->SetTitle('Export du traitement ' . $traitement['REF']);

        $nom   = esc($traitement['NOM'] ?? 'Non renseigné');
        $ref   = esc($traitement['REF'] ?? 'Non renseigné');
        $dc    = esc($traitement['DATECREATION'] ?? 'Non renseignée');
        $dm    = esc($traitement['DATEMAJ'] ?? 'Non renseignée');
        $final = esc($traitement['FINALITE'] ?? 'Non renseignée');
        $sens  = esc($traitement['DONNEESSENSIBLES'] ?? 'Non renseignées');
        $hors  = esc($traitement['TRANSFERT_HORS_UE'] ?? 'Non renseigné');

        $html = "
            <h1>Traitement : $nom</h1>

            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 10pt;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                    border: 1px solid #000;
                    padding: 6px;
                    text-align: left;
                }
                td {
                    border: 1px solid #000;
                    padding: 6px;
                }
            </style>

            <table>
                <tr><th>Référence</th><td>$ref</td></tr>
                <tr><th>Date création</th><td>$dc</td></tr>
                <tr><th>Date mise à jour</th><td>$dm</td></tr>
                <tr><th>Finalité</th><td>$final</td></tr>
                <tr><th>Données sensibles</th><td>$sens</td></tr>
                <tr><th>Transferts hors UE</th><td>$hors</td></tr>
            </table>
        ";

        $pdf->writeHTML($html, true, false, true, false, '');

        // Nettoyage complet des buffers AVANT la sortie PDF
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        return $pdf->Output('export_traitement_' . $ref . '.pdf', 'I');
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
            // Génération du badge pour "Données sensibles"
            $badgeSensible = '';
            if (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui') {
                $badgeSensible = '<span class="badge badge-oui">Oui</span>';
            } else {
                $badgeSensible = '<span class="badge badge-non">Non</span>';
            }

            // Génération du badge pour "Transferts hors UE"
            $badgeTransfert = '';
            if (trim(strtolower($t['TRANSFERT_HORS_UE'])) === 'oui' || $t['TRANSFERT_HORS_UE'] == 1) {
                $badgeTransfert = '<span class="badge badge-oui">Oui</span>';
            } else {
                $badgeTransfert = '<span class="badge badge-non">Non</span>';
            }

            $html .= '
                <tr onclick="window.location=\'' . site_url('pageInfo/edit/' . $t['REF']) . '\';" class="clickable-row">
                    <td><strong>' . esc($t['NOM']) . '</strong></td>
                    <td><code>' . esc($t['REF']) . '</code></td>
                    <td>' . esc($t['DATECREATION']) . '</td>
                    <td>' . esc($t['DATEMAJ']) . '</td>
                    <td class="finalite">' . esc($t['FINALITE']) . '</td>
                    <td>' . $badgeSensible . '</td>
                    <td>' . $badgeTransfert . '</td>
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