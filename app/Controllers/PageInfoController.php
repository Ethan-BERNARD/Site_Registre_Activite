<?php namespace App\Controllers;

use App\Models\ActionPageInfo;

class PageInfoController extends BaseController
{
    protected $model;

    public function __construct() {
        $this->model = new ActionPageInfo();
    }

    public function index() {
        $data = $this->loadCommonData();
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('rssi/v_rssi_traitements_detail', $data);
    }

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

    private function loadCommonData() {
        return [
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

    public function save() {
        $mode = $this->request->getPost('mode');
        $ref  = $this->request->getPost('id_traitement');

        /* ---------------------------------------------------------
           1) TRAITEMENT PRINCIPAL
        --------------------------------------------------------- */

        $traitementData = [
            'REF'            => $this->request->getPost('ref'),
            'NOM'            => $this->request->getPost('nom'),
            'DATECREATION'   => $this->request->getPost('date_crea'),
            'DATEMAJ'        => $this->request->getPost('date_maj'),
            'TRANSFERTHHORSUE' => $this->request->getPost('checkboxTransfert') ? 1 : 0,
        ];

        if ($mode === 'create') {
            $ref = $this->model->insertTraitement($traitementData);
        } else {
            $this->model->updateTraitement($ref, $traitementData);
            $this->model->deleteAllBlocs($ref);
        }

        /* ---------------------------------------------------------
           2) ACTEURS
        --------------------------------------------------------- */
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
                    'IDTYPE'  => $this->request->getPost('acteur_type')[$i] ?: null,
                ]);

                $this->model->linkActeurToTraitement($idActeur, $ref);
            }
        }

        /* ---------------------------------------------------------
           3) FINALITÉS
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           4) CATÉGORIES DCP
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           5) DONNÉES SENSIBLES
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           6) PERSONNES CONCERNÉES
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           7) DESTINATAIRES
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           8) MESURES DE SÉCURITÉ
        --------------------------------------------------------- */
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

        /* ---------------------------------------------------------
           9) TRANSFERTS HORS UE
        --------------------------------------------------------- */
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

        return redirect()->to('/pageInfo/edit/'.$ref)->with('success', 'Traitement sauvegardé');
    }
}