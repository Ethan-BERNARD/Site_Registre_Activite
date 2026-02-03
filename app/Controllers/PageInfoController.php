<?php namespace App\Controllers;

use App\Models\ActionPageInfo;

class PageInfoController extends BaseController
{
    protected $actionPageInfo;

    public function __construct() {
        $this->actionPageInfo = new ActionPageInfo();
    }

    /**
     * Mode création : formulaire vide
     */
    public function index() {

        $data = $this->loadCommonData();

        // Aucune donnée de traitement → création
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('v_rssi_traitement_detail', $data);
    }

    /**
     * Mode édition : formulaire prérempli
     */
    public function edit($id) {

        $data = $this->loadCommonData();

        // Récupération du traitement existant
        $data['traitement'] = $this->actionPageInfo->getTraitementById($id);

        if (!$data['traitement']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Traitement introuvable");
        }

        $data['mode'] = 'edit';

        $data['traitement'] = $this->actionPageInfo->getTraitementById($id);
        $data['acteurs'] = $this->actionPageInfo->getActeursByTraitement($id);
        $data['finalites'] = $this->actionPageInfo->getFinalitesByTraitement($id);
        $data['categories'] = $this->actionPageInfo->getCategoriesByTraitement($id);
        $data['sensibles'] = $this->actionPageInfo->getSensiblesByTraitement($id);
        $data['personnes'] = $this->actionPageInfo->getPersonnesByTraitement($id);
        $data['destinataires'] = $this->actionPageInfo->getDestinatairesByTraitement($id);
        $data['securites'] = $this->actionPageInfo->getSecuritesByTraitement($id);
        $data['transferts'] = $this->actionPageInfo->getTransfertsByTraitement($id);

        return view('v_rssi_traitement_detail', $data);
    }

    /**
     * Chargement des listes communes (selects)
     */
    private function loadCommonData() {
        return [
            'categDCP'            => $this->actionPageInfo->getCategDCP(),
            'categDCPSensible'    => $this->actionPageInfo->getCategDCPSensible(),
            'personnesConcerne'   => $this->actionPageInfo->getPersonnesConcerne(),
            'typeActeur'          => $this->actionPageInfo->getTypeActeur(),
            'typeMesureSecurite'  => $this->actionPageInfo->getTypeMesureSecurite(),
            'typeDestinataire'    => $this->actionPageInfo->getTypeDestinataire(),
            'typeGarantie'        => $this->actionPageInfo->getTypeGarantie(),
            'pays'                => $this->actionPageInfo->getPays(),
        ];
    }

    public function save() {
        $mode = $this->request->getPost('mode');
        $id = $this->request->getPost('id_traitement');

        /* ---------------------------------------------------------
        1) TRAITEMENT PRINCIPAL
        --------------------------------------------------------- */

        $traitementData = [
            'nom'        => $this->request->getPost('nom'),
            'ref'        => $this->request->getPost('ref'),
            'date_crea'  => $this->request->getPost('date_crea'),
            'date_maj'   => $this->request->getPost('date_maj'),
            'transfert'  => $this->request->getPost('checkboxTransfert') ? 1 : 0,
        ];

        if ($mode === 'create') {
            // INSERT
            $id = $this->actionPageInfo->insertTraitement($traitementData);
        } else {
            // UPDATE
            $this->actionPageInfo->updateTraitement($id, $traitementData);

            // On supprime tous les blocs liés pour les remplacer
            $this->actionPageInfo->deleteAllBlocs($id);
        }

        /* ---------------------------------------------------------
        2) ACTEURS
        --------------------------------------------------------- */

        $noms = $this->request->getPost('acteur_nom');
        if ($noms) {
            foreach ($noms as $i => $nom) {
                $this->actionPageInfo->insertActeur([
                    'IDTRAITEMENT' => $id,
                    'NOM'          => $nom,
                    'ADRESSE'      => $this->request->getPost('acteur_adresse')[$i],
                    'CP'           => $this->request->getPost('acteur_cp')[$i],
                    'VILLE'        => $this->request->getPost('acteur_ville')[$i],
                    'PAYS'         => $this->request->getPost('acteur_pays')[$i],
                    'TEL'          => $this->request->getPost('acteur_tel')[$i],
                    'MAIL'         => $this->request->getPost('acteur_mail')[$i],
                    'IDTYPE'       => $this->request->getPost('categorie_type')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        3) FINALITÉS
        --------------------------------------------------------- */

        $finalites = $this->request->getPost('finalite');
        if ($finalites) {
            foreach ($finalites as $i => $finalite) {
                $this->actionPageInfo->insertFinalite([
                    'IDTRAITEMENT' => $id,
                    'FINALITE'     => $finalite,
                    'EST_PRINCIPAL'=> isset($this->request->getPost('est_principal')[$i]) ? 1 : 0,
                ]);
            }
        }

        /* ---------------------------------------------------------
        4) CATÉGORIES DCP
        --------------------------------------------------------- */

        $catDesc = $this->request->getPost('categorie_description');
        if ($catDesc) {
            foreach ($catDesc as $i => $desc) {
                $this->actionPageInfo->insertCategorie([
                    'IDTRAITEMENT' => $id,
                    'DESCRIPTION'  => $desc,
                    'DUREE'        => $this->request->getPost('categorie_duree')[$i],
                    'IDCATEGDCP'   => $this->request->getPost('categorie_type')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        5) DONNÉES SENSIBLES
        --------------------------------------------------------- */

        $sensDesc = $this->request->getPost('sensible_description');
        if ($sensDesc) {
            foreach ($sensDesc as $i => $desc) {
                $this->actionPageInfo->insertSensible([
                    'IDTRAITEMENT' => $id,
                    'DESCRIPTION'  => $desc,
                    'DUREE'        => $this->request->getPost('sensible_duree')[$i],
                    'IDCATEGDCPSENSIBLE' => $this->request->getPost('sensible_categorie')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        6) PERSONNES CONCERNÉES
        --------------------------------------------------------- */

        $persDesc = $this->request->getPost('personne_description');
        if ($persDesc) {
            foreach ($persDesc as $i => $desc) {
                $this->actionPageInfo->insertPersonne([
                    'IDTRAITEMENT' => $id,
                    'IDCATEGPERSONNECONCERNE' => $desc,
                    'PRECISION'    => $this->request->getPost('personne_precision')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        7) DESTINATAIRES
        --------------------------------------------------------- */

        $destDesc = $this->request->getPost('destinataire_description');
        if ($destDesc) {
            foreach ($destDesc as $i => $desc) {
                $this->actionPageInfo->insertDestinataire([
                    'IDTRAITEMENT' => $id,
                    'IDTYPE'       => $desc,
                    'PRECISION'    => $this->request->getPost('destinataire_precision')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        8) MESURES DE SÉCURITÉ
        --------------------------------------------------------- */

        $secDesc = $this->request->getPost('securite_description');
        if ($secDesc) {
            foreach ($secDesc as $i => $desc) {
                $this->actionPageInfo->insertSecurite([
                    'IDTRAITEMENT' => $id,
                    'IDTYPE'       => $desc,
                    'PRECISION'    => $this->request->getPost('securite_precision')[$i],
                ]);
            }
        }

        /* ---------------------------------------------------------
        9) TRANSFERTS HORS UE
        --------------------------------------------------------- */

        $transDest = $this->request->getPost('transfert_destinataire');
        if ($transDest) {
            foreach ($transDest as $i => $dest) {
                $this->actionPageInfo->insertTransfert([
                    'IDTRAITEMENT' => $id,
                    'DESTINATAIRE' => $dest,
                    'IDPAYS'       => $this->request->getPost('transfert_pays')[$i],
                    'IDTYPE'       => $this->request->getPost('transfert_garantie')[$i],
                    'LIEN'         => $this->request->getPost('transfert_lien')[$i],
                ]);
            }
        }

        return redirect()->to('/pageInfo/edit/'.$id)->with('success', 'Traitement sauvegardé');
    }
}