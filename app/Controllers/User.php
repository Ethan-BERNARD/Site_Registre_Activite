<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsUser;
use App\Models\ActionPageInfo;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    private $authentif;
    private $idUser;
    private $data = [];
    private $actUser;

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

        $this->idUser = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actUser = new ActionsUser($this->idUser);
    }

    public function index()
    {
        $stats = $this->actUser->getDashboardStats();
        
        return view('user/v_user_accueil', array_merge($this->data, [
            'stats' => $stats
        ]));
    }

    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    // CONSULTATION DU TABLEAU
    public function tableau()
    {
        $this->logConsultationListe();

        $search = $this->request->getGet('search');
        $traitements = $this->actUser->getTraitementsAvecFinaliteEtSensibles($search);

        return view('user/v_user_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    // RECHERCHE AJAX
    public function searchAjax()
    {
        $q = $this->request->getGet('q');
        $traitements = $this->actUser->getTraitementsAvecFinaliteEtSensibles($q);

        $html = '';

        foreach ($traitements as $t) {
            $badgeSensibles = (trim(strtolower($t['DONNEESSENSIBLES'])) === 'oui')
                ? '<span class="badge badge-oui">Oui</span>'
                : '<span class="badge badge-non">Non</span>';

            $badgeTransfert = (trim(strtolower($t['TRANSFERT_HORS_UE'])) === 'oui' || $t['TRANSFERT_HORS_UE'] == 1)
                ? '<span class="badge badge-oui">Oui</span>'
                : '<span class="badge badge-non">Non</span>';

            $html .= '
                <tr class="clickable-row"
                    data-sensibles="' . strtolower($t['DONNEESSENSIBLES']) . '"
                    data-transferts="' . strtolower($t['TRANSFERT_HORS_UE']) . '"
                    data-date="' . $t['DATECREATION'] . '">
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';"><strong>' . esc($t['NOM']) . '</strong></td>
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';"><code>' . esc($t['REF']) . '</code></td>
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';">' . esc($t['DATECREATION']) . '</td>
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';">' . esc($t['DATEMAJ']) . '</td>
                    <td class="finalite" onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';">' . esc($t['FINALITE']) . '</td>
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';">' . $badgeSensibles . '</td>
                    <td onclick="window.location=\'' . site_url('user/consulter/' . $t['REF']) . '\';">' . $badgeTransfert . '</td>
                </tr>
            ';
        }

        return $this->response->setBody($html);
    }

    // CRÉATION D'UN NOUVEAU TRAITEMENT
    public function creer()
    {
        $model = new ActionPageInfo();
        $data = $this->loadCommonData($model);
        $data['identite'] = $this->data['identite'];
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('user/v_user_traitement_form', $data);
    }

    // CONSULTATION D'UN TRAITEMENT (lecture seule)
    public function consulter($ref)
    {
        $traitement = $this->actUser->getTraitementById($ref);

        if (!$traitement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Traitement introuvable");
        }

        $this->logConsultationTraitement($ref);

        $model = new ActionPageInfo();

        return view('user/v_user_traitement_detail', [
            'identite'   => $this->data['identite'],
            'traitement' => $traitement,
            'acteurs'        => $model->getActeursByTraitement($ref),
            'finalites'      => $model->getFinalitesByTraitement($ref),
            'categories'     => $model->getCategoriesByTraitement($ref),
            'sensibles'      => $model->getSensiblesByTraitement($ref),
            'personnes'      => $model->getPersonnesByTraitement($ref),
            'destinataires'  => $model->getDestinatairesByTraitement($ref),
            'securites'      => $model->getSecuritesByTraitement($ref),
            'transferts'     => $model->getTransfertsByTraitement($ref),
        ]);
    }

    // ENREGISTREMENT D'UN NOUVEAU TRAITEMENT
    public function save()
    {
        $model = new ActionPageInfo();
        
        $traitementData = [
            'NOM'            => $this->request->getPost('nom'),
            'DATECREATION'   => date('Y-m-d'),
            'DATEMAJ'        => date('Y-m-d'),
            'TRANSFERTHHORSUE' => $this->request->getPost('checkboxTransfert') ? 1 : 0,
        ];

        $ref = $model->insertTraitement($traitementData);

        // Insertion des blocs (acteurs, finalités, etc.)
        $this->saveBlocs($model, $ref);

        return redirect()->to('/user/tableau')->with('success', 'Traitement créé avec succès');
    }

    private function saveBlocs($model, $ref)
    {
        // ACTEURS
        $noms = $this->request->getPost('acteur_nom');
        if ($noms) {
            foreach ($noms as $i => $nom) {
                if (trim($nom) === '') continue;

                $idActeur = $model->insertActeur([
                    'NOM'     => $nom,
                    'ADRESSE' => $this->request->getPost('acteur_adresse')[$i] ?? '',
                    'CP'      => $this->request->getPost('acteur_cp')[$i] ?? '',
                    'VILLE'   => $this->request->getPost('acteur_ville')[$i] ?? '',
                    'PAYS'    => $this->request->getPost('acteur_pays')[$i] ?? '',
                    'TEL'     => $this->request->getPost('acteur_tel')[$i] ?? '',
                    'MAIL'    => $this->request->getPost('acteur_mail')[$i] ?? '',
                    'IDTYPE'  => $this->request->getPost('acteur_type')[$i] ?: null,
                ]);
                $model->linkActeurToTraitement($idActeur, $ref);
            }
        }

        // FINALITÉS
        $finalites = $this->request->getPost('finalite');
        if ($finalites) {
            foreach ($finalites as $i => $libelle) {
                if (trim($libelle) === '') continue;
                $model->insertFinalite([
                    'REF'          => $ref,
                    'LIBELLE'      => $libelle,
                    'ESTPRINCIPAL' => isset($this->request->getPost('est_principal')[$i]) ? 1 : 0,
                ]);
            }
        }

        // CATÉGORIES DCP
        $catDesc = $this->request->getPost('categorie_description');
        if ($catDesc) {
            foreach ($catDesc as $i => $desc) {
                $idCateg = $this->request->getPost('categorie_type')[$i] ?? null;
                if (trim($desc) === '' || !$idCateg) continue;

                $model->insertCategorie([
                    'REF'                => $ref,
                    'DESCRIPTION'        => $desc,
                    'DUREECONSERVATION'  => $this->request->getPost('categorie_duree')[$i] ?? '',
                    'IDCATEG'            => $idCateg,
                ]);
            }
        }

        // DONNÉES SENSIBLES
        $sensDesc = $this->request->getPost('sensible_description');
        if ($sensDesc) {
            foreach ($sensDesc as $i => $desc) {
                $idCateg = $this->request->getPost('sensible_categorie')[$i] ?? null;
                if (trim($desc) === '' || !$idCateg) continue;

                $model->insertSensible([
                    'REF'                => $ref,
                    'DESCRIPTION'        => $desc,
                    'DUREECONSERVATION'  => $this->request->getPost('sensible_duree')[$i] ?? '',
                    'IDCATEG'            => $idCateg,
                ]);
            }
        }

        // PERSONNES CONCERNÉES
        $persDesc = $this->request->getPost('personne_description');
        if ($persDesc) {
            foreach ($persDesc as $i => $idCat) {
                if (!$idCat) continue;
                $model->insertPersonne([
                    'REF'        => $ref,
                    'ID_EST_DE_CATEGORIE_PERSONNE' => $idCat,
                    'PRECIS'     => $this->request->getPost('personne_precision')[$i] ?? '',
                ]);
            }
        }

        // DESTINATAIRES
        $destDesc = $this->request->getPost('destinataire_description');
        if ($destDesc) {
            foreach ($destDesc as $i => $idType) {
                if (!$idType) continue;
                $model->insertDestinataire([
                    'REF'        => $ref,
                    'ID_EST_DE_TYPE_DESTINATAIRE' => $idType,
                    'PRECIS'     => $this->request->getPost('destinataire_precision')[$i] ?? '',
                ]);
            }
        }

        // MESURES DE SÉCURITÉ
        $secDesc = $this->request->getPost('securite_description');
        if ($secDesc) {
            foreach ($secDesc as $i => $idType) {
                if (!$idType) continue;
                $model->insertSecurite([
                    'REF'        => $ref,
                    'ID_EST_DE_TYPE_DE_MESURE' => $idType,
                    'PRECIS'     => $this->request->getPost('securite_precision')[$i] ?? '',
                ]);
            }
        }

        // TRANSFERTS HORS UE
        $transDest = $this->request->getPost('transfert_destinataire');
        if ($transDest) {
            foreach ($transDest as $i => $dest) {
                $paysId     = $this->request->getPost('transfert_pays')[$i] ?? null;
                $garantieId = $this->request->getPost('transfert_garantie')[$i] ?? null;
                if (trim($dest) === '' || !$paysId || !$garantieId) continue;

                $model->insertTransfert([
                    'REF'                     => $ref,
                    'DESTINATAIRE'            => $dest,
                    'ID_TRANSFERT_VERS_PAYS'  => $paysId,
                    'ID_GARANTIE_APPLIQUEE'   => $garantieId,
                    'LIENDOC'                 => $this->request->getPost('transfert_lien')[$i] ?? '',
                ]);
            }
        }
    }

    private function loadCommonData($model)
    {
        return [
            'categDCP'            => $model->getCategDCP(),
            'categDCPSensible'    => $model->getCategDCPSensible(),
            'personnesConcerne'   => $model->getPersonnesConcerne(),
            'typeActeur'          => $model->getTypeActeur(),
            'typeMesureSecurite'  => $model->getTypeMesureSecurite(),
            'typeDestinataire'    => $model->getTypeDestinataire(),
            'typeGarantie'        => $model->getTypeGarantie(),
            'pays'                => $model->getPays(),
        ];
    }

    private function logConsultationListe()
    {
        $this->actUser->logAction('CONSULTATION_LISTE', 'Consultation de la liste des traitements');
    }

    private function logConsultationTraitement($ref)
    {
        $this->actUser->logAction('CONSULTATION', "Consultation du traitement $ref");
    }
}