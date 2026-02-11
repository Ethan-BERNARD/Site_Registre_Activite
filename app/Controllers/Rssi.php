<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsRssi;
use App\Models\ActionPageInfo;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use TCPDF;

/**
 * Contrôleur RSSI (administrateur) : gestion avancée du registre de traitement.
 * Permet la consultation, création, modification, suppression et export des traitements RGPD.
 */
class Rssi extends BaseController
{
    /** @var \CodeIgniter\Session\Session Session utilisateur */
    protected $session;
    
    /** @var Authentif Service d'authentification */
    private $authentif;
    
    /** @var int Identifiant du RSSI courant */
    private $idRssi;
    
    /** @var array Données communes à transmettre aux vues */
    private $data = [];
    
    /** @var ActionsRssi Gestionnaire d'actions métier RSSI */
    private $actRssi;

    /**
     * Initialise le contrôleur à chaque requête.
     * Vérifie l'authentification, les droits d'accès RSSI et configure les en-têtes de cache.
     *
     * @param RequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param LoggerInterface $logger Logger
     * @return void
     */
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

        if ($this->session->get('DROIT') !== 'AD') {
            if ($this->session->get('DROIT') === 'US') {
                redirect()->to('/user')->send();
            } else {
                $this->session->destroy();
                redirect()->to('/anonyme')->send();
            }
            exit;
        }

        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");

        $this->idRssi = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actRssi = new ActionsRssi($this->idRssi);
        $this->model = new ActionPageInfo();
    }

    /**
     * Page d'accueil du tableau de bord RSSI.
     * Affiche les statistiques globales du registre de traitement.
     *
     * @return string Vue du tableau de bord
     */
    public function index()
    {
        $stats = $this->actRssi->getDashboardStats();
        
        return view('rssi/v_rssi_accueil', array_merge($this->data, [
            'stats' => $stats
        ]));
    }

    /**
     * Affiche la page de détails des traitements (mode création).
     *
     * @return string Vue des détails de traitement
     */
    public function indexDetails() {
        $data = $this->loadCommonData();
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('rssi/v_rssi_traitements_detail', $data);
    }

    /**
     * Déconnecte le RSSI et redirige vers la page de connexion.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers /anonyme
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    /**
     * Affiche le tableau des traitements.
     * Enregistre la consultation dans les logs.
     *
     * @return string Vue du tableau des traitements
     */
    public function tab()
    {
        $this->logConsultationListe();

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

        return view('rssi/v_rssi_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    /**
     * Affiche l'historique des logs système avec filtrage par limite.
     *
     * @return string Vue des logs
     */
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

    /**
     * Affiche le formulaire de sélection pour l'export PDF.
     *
     * @return string Vue du formulaire d'export
     */
    public function exportPDF()
    {
        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles();

        return view('rssi/v_rssi_export_form', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    /**
     * Génère un PDF contenant les traitements sélectionnés.
     * Enregistre l'export dans les logs.
     *
     * @return \CodeIgniter\HTTP\Response|string PDF généré ou redirection en cas d'erreur
     */
    public function genererPDF()
    {
        $selection = $this->request->getPost('selection');
        
        if ($selection && $selection !== 'all') {
            $refs = json_decode($selection, true);
            
            if (empty($refs)) {
                return redirect()->back()->with('error', 'Aucun traitement sélectionné.');
            }
            
            $this->logExportSelection($refs);
            
            $pdf = new TCPDF();
            $pdf->SetCreator('Registre RGPD');
            $pdf->SetAuthor($this->data['identite']);
            $pdf->AddPage('L');
            $pdf->SetTitle('Export sélectif du registre');

            $traitements = [];
            foreach ($refs as $ref) {
                $t = $this->actRssi->getTraitementById($ref);
                if ($t) {
                    $traitements[] = $t;
                }
            }

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

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-Type: application/pdf');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            return $pdf->Output('export_selection.pdf', 'I');
        }
        
    }

    /**
     * Affiche le tableau des traitements avec support de recherche.
     *
     * @return string Vue du tableau avec résultats de recherche
     */
    public function tableau()
    {
        $search = $this->request->getGet('search');

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($search);

        return view('rssi/v_rssi_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ], ['saveData' => true]);
    }

    /**
     * Affiche le détail d'un traitement spécifique.
     * Enregistre la consultation dans les logs.
     *
     * @param int $ref Référence du traitement
     * @return string Vue détaillée du traitement
     * @throws \CodeIgniter\Exceptions\PageNotFoundException Si le traitement n'existe pas
     */
    public function detail($ref)
    {
        $traitement = $this->actRssi->getTraitementById($ref);

        if (!$traitement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Traitement introuvable");
        }

        $this->logConsultationTraitement($ref);

        return view('rssi/v_rssi_traitement_detail', [
            'identite'   => $this->data['identite'],
            'traitement' => $traitement
        ]);
    }

    /**
     * Point d'entrée AJAX pour la recherche dynamique de traitements.
     * Retourne le HTML des lignes du tableau filtrées avec checkbox pour export.
     *
     * @return \CodeIgniter\HTTP\Response Réponse HTTP contenant le HTML généré
     */
    public function searchAjax()
    {
        $q = $this->request->getGet('q');

        $traitements = $this->actRssi->getTraitementsAvecFinaliteEtSensibles($q);

        $html = '';

        foreach ($traitements as $t) {
            $badgeSensibles = '';
            if (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui') {
                $badgeSensibles = '<span class="badge badge-oui">Oui</span>';
            } else {
                $badgeSensibles = '<span class="badge badge-non">Non</span>';
            }

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

    /**
     * Affiche le formulaire de création d'un nouveau traitement.
     *
     * @return string Vue du formulaire de création
     */
    public function create() {
        $data = $this->loadCommonData();
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('rssi/v_rssi_traitements_detail', $data);
    }

    /**
     * Affiche le formulaire d'édition d'un traitement existant.
     * Charge toutes les données associées au traitement.
     *
     * @param int $ref Référence du traitement à éditer
     * @return string Vue du formulaire d'édition
     * @throws \CodeIgniter\Exceptions\PageNotFoundException Si le traitement n'existe pas
     */
    public function edit($ref) {
        $data = $this->loadCommonData();

        $data['traitement'] = $this->model->getTraitementByRef($ref);
        if (!$data['traitement']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Traitement introuvable");
        }

        $data['mode'] = 'edit';

        $data['acteurs']        = $this->model->getActeursByTraitement($ref);
        $data['finalites']      = $this->model->getFinalitesByTraitement($ref);
        $data['categories']     = $this->model->getCategoriesByTraitement($ref);
        $data['sensibles']      = $this->model->getSensiblesByTraitement($ref);
        $data['personnes']      = $this->model->getPersonnesByTraitement($ref);
        $data['destinataires']  = $this->model->getDestinatairesByTraitement($ref);
        $data['securites']      = $this->model->getSecuritesByTraitement($ref);
        $data['transferts']     = $this->model->getTransfertsByTraitement($ref);

        return view('rssi/v_rssi_traitements_detail', $data);
    }

    /**
     * Enregistre un traitement (création ou modification) avec tous ses blocs associés.
     * Gère automatiquement les dates de création et de mise à jour.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers le tableau avec message de succès
     */
    public function save() {
        $mode = $this->request->getPost('mode');
        $ref  = $this->request->getPost('id_traitement');

        $traitementData = [
            'NOM'            => $this->request->getPost('nom'),
            'TRANSFERTHHORSUE' => $this->request->getPost('checkboxTransfert') ? 1 : 0,
        ];

        if ($mode === 'create') {
            $traitementData['DATECREATION'] = date('Y-m-d');
            $traitementData['DATEMAJ'] = date('Y-m-d');
            
            $ref = $this->model->insertTraitement($traitementData);
        } else {
            $traitementData['DATEMAJ'] = date('Y-m-d');
            $this->model->updateTraitement($ref, $traitementData);
            $this->model->deleteAllBlocs($ref);
        }

        $noms = $this->request->getPost('acteur_nom');
        if ($noms) {
            foreach ($noms as $i => $nom) {

                if (trim($nom) === '') continue;

                $idActeur = $this->model->insertActeur([
                    'NOM'     => $nom,
                    'ADRESSE' => $this->request->getPost('acteur_adresse')[$i] ?? '',
                    'CP'      => $this->request->getPost('acteur_cp')[$i] ?? '',
                    'VILLE'   => $this->request->getPost('acteur_ville')[$i] ?? '',
                    'PAYS'    => $this->request->getPost('acteur_pays')[$i] ?? '',
                    'TEL'     => $this->request->getPost('acteur_tel')[$i] ?? '',
                    'MAIL'    => $this->request->getPost('acteur_mail')[$i] ?? '',
                    'IDTYPE'  => $this->request->getPost('acteur_type')[$i] ?? null,
                ]);

                $this->model->linkActeurToTraitement($idActeur, $ref);
            }
        }

        $finalites = $this->request->getPost('finalite');
        if ($finalites) {
            foreach ($finalites as $i => $libelle) {

                if (trim($libelle) === '') continue;

                $this->model->insertFinalite([
                    'REF'          => $ref,
                    'LIBELLE'      => $libelle,
                    'ESTPRINCIPAL' => isset($this->request->getPost('est_principal')[$i]) ? 1 : 0,
                ]);
            }
        }

        $catDesc = $this->request->getPost('categorie_description');
        if ($catDesc) {
            foreach ($catDesc as $i => $desc) {

                $idCateg = $this->request->getPost('categorie_type')[$i] ?? null;

                if (trim($desc) === '' || !$idCateg) continue;

                $this->model->insertCategorie([
                    'REF'                => $ref,
                    'DESCRIPTION'        => $desc,
                    'DUREECONSERVATION'  => $this->request->getPost('categorie_duree')[$i] ?? '',
                    'IDCATEG'            => $idCateg,
                ]);
            }
        }

        $sensDesc = $this->request->getPost('sensible_description');
        if ($sensDesc) {
            foreach ($sensDesc as $i => $desc) {

                $idCateg = $this->request->getPost('sensible_categorie')[$i] ?? null;

                if (trim($desc) === '' || !$idCateg) continue;

                $this->model->insertSensible([
                    'REF'                => $ref,
                    'DESCRIPTION'        => $desc,
                    'DUREECONSERVATION'  => $this->request->getPost('sensible_duree')[$i] ?? '',
                    'IDCATEG'            => $idCateg,
                ]);
            }
        }

        $persDesc = $this->request->getPost('personne_description');
        if ($persDesc) {
            foreach ($persDesc as $i => $idCat) {

                if (!$idCat) continue;

                $this->model->insertPersonne([
                    'REF'        => $ref,
                    'ID_EST_DE_CATEGORIE_PERSONNE' => $idCat,
                    'PRECIS'     => $this->request->getPost('personne_precision')[$i] ?? '',
                ]);
            }
        }

        $destDesc = $this->request->getPost('destinataire_description');
        if ($destDesc) {
            foreach ($destDesc as $i => $idType) {

                if (!$idType) continue;

                $this->model->insertDestinataire([
                    'REF'        => $ref,
                    'ID_EST_DE_TYPE_DESTINATAIRE' => $idType,
                    'PRECIS'     => $this->request->getPost('destinataire_precision')[$i] ?? '',
                ]);
            }
        }

        $secDesc = $this->request->getPost('securite_description');
        if ($secDesc) {
            foreach ($secDesc as $i => $idType) {

                if (!$idType) continue;

                $this->model->insertSecurite([
                    'REF'        => $ref,
                    'ID_EST_DE_TYPE_DE_MESURE' => $idType,
                    'PRECIS'     => $this->request->getPost('securite_precision')[$i] ?? '',
                ]);
            }
        }

        $transDest = $this->request->getPost('transfert_destinataire');
        if ($transDest) {
            foreach ($transDest as $i => $dest) {

                $paysId     = $this->request->getPost('transfert_pays')[$i] ?? null;
                $garantieId = $this->request->getPost('transfert_garantie')[$i] ?? null;

                if (trim($dest) === '' || !$paysId || !$garantieId) continue;

                $this->model->insertTransfert([
                    'REF'                     => $ref,
                    'DESTINATAIRE'            => $dest,
                    'ID_TRANSFERT_VERS_PAYS'  => $paysId,
                    'ID_GARANTIE_APPLIQUEE'   => $garantieId,
                    'LIENDOC'                 => $this->request->getPost('transfert_lien')[$i] ?? '',
                ]);
            }
        }
        session()->setFlashdata('success', 'Le traitement a bien été enregistré.');
        return redirect()->to('/rssi/tableau')->with('success', 'Traitement sauvegardé');
    }

    /**
     * Charge toutes les données de référence nécessaires pour les formulaires.
     *
     * @return array Tableau associatif contenant toutes les listes de référence
     */
    private function loadCommonData() {
        return [
            'identite'            => session()->get('LOGIN'),
            'categDCP'            => $this->model->getCategDCP(),
            'categDCPSensible'    => $this->model->getCategDCPSensible(),
            'personnesConcerne'   => $this->model->getPersonnesConcerne(),
            'typeActeur'          => $this->model->getTypeActeur(),
            'typeMesureSecurite'  => $this->model->getTypeMesureSecurite(),
            'typeDestinataire'    => $this->model->getTypeDestinataire(),
            'typeGarantie'        => $this->model->getTypeGarantie(),
            'pays'                => $this->model->getPays(),
        ];
    }

    /**
     * Enregistre dans les logs la consultation de la liste des traitements.
     *
     * @return void
     */
    private function logConsultationListe()
    {
        $this->actRssi->logAction('CONSULTATION_LISTE', 'Consultation de la liste des traitements');
    }

    /**
     * Enregistre dans les logs la consultation d'un traitement spécifique.
     *
     * @param int $ref Référence du traitement consulté
     * @return void
     */
    private function logConsultationTraitement($ref)
    {
        $this->actRssi->logAction('CONSULTATION', "Consultation du traitement $ref");
    }

    /**
     * Enregistre dans les logs un export PDF global du registre.
     *
     * @return void
     */
    private function logExportGlobal()
    {
        $this->actRssi->logAction('EXPORT', 'Export PDF global du registre');
    }

    /**
     * Enregistre dans les logs un export PDF d'un traitement spécifique.
     *
     * @param int $idTraitement Référence du traitement exporté
     * @return void
     */
    private function logExportTraitement($idTraitement)
    {
        $this->actRssi->logAction('EXPORT', "Export PDF du traitement $idTraitement");
    }

    /**
     * Enregistre dans les logs un export PDF de traitements sélectionnés.
     *
     * @param array $refs Liste des références des traitements exportés
     * @return void
     */
    private function logExportSelection($refs)
    {
        $count = count($refs);
        $this->actRssi->logAction('EXPORT', "Export PDF de $count traitement(s) : " . implode(', ', $refs));
    }
}