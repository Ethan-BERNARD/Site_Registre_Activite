<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsUser;
use App\Models\ActionPageInfo;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Contrôleur utilisateur standard : gestion du registre de traitement pour les utilisateurs non-administrateurs.
 * Permet la consultation et la création de traitements RGPD.
 */
class User extends BaseController
{
    /** @var Authentif Service d'authentification */
    private $authentif;
    
    /** @var int Identifiant de l'utilisateur courant */
    private $idUser;
    
    /** @var array Données communes à transmettre aux vues */
    private $data = [];
    
    /** @var ActionsUser Gestionnaire d'actions métier utilisateur */
    private $actUser;

    /**
     * Initialise le contrôleur à chaque requête.
     * Vérifie l'authentification et configure les en-têtes de cache.
     * CORRECTION SÉCURITÉ : Ajout de headers de sécurité supplémentaires.
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

        // Vérification de l'authentification
        if (!$this->session->get('ID')) {
            redirect()->to('/anonyme')->send();
            exit;
        }

        // CORRECTION SÉCURITÉ : Headers de sécurité renforcés
        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");
        $this->response->setHeader("X-Content-Type-Options", "nosniff");
        $this->response->setHeader("X-Frame-Options", "DENY");
        $this->response->setHeader("X-XSS-Protection", "1; mode=block");
        $this->response->setHeader("Referrer-Policy", "strict-origin-when-cross-origin");

        $this->idUser = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actUser = new ActionsUser($this->idUser);
    }

    /**
     * Page d'accueil du tableau de bord utilisateur.
     * Affiche les statistiques du registre de traitement.
     *
     * @return string Vue du tableau de bord
     */
    public function index()
    {
        $stats = $this->actUser->getDashboardStats();
        
        return view('user/v_user_accueil', array_merge($this->data, [
            'stats' => $stats
        ]));
    }

    /**
     * Déconnecte l'utilisateur et redirige vers la page de connexion.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers /anonyme
     */
    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    /**
     * Affiche le tableau complet des traitements avec recherche.
     * Enregistre la consultation dans les logs.
     *
     * @return string Vue du tableau des traitements
     */
    public function tableau()
    {
        $this->logConsultationListe();

        // CORRECTION SÉCURITÉ : Validation et nettoyage du paramètre de recherche
        $search = $this->request->getGet('search');
        if ($search !== null) {
            $search = trim(strip_tags($search));
            // Limiter la longueur de recherche
            $search = mb_substr($search, 0, 100);
        }

        $traitements = $this->actUser->getTraitementsAvecFinaliteEtSensibles($search);

        return view('user/v_user_traitements', [
            'identite' => $this->data['identite'],
            'traitements' => $traitements
        ]);
    }

    /**
     * Point d'entrée AJAX pour la recherche dynamique de traitements.
     * Retourne le HTML des lignes du tableau filtrées.
     * CORRECTION SÉCURITÉ : Échappement complet de tous les attributs HTML et contenu.
     *
     * @return \CodeIgniter\HTTP\Response Réponse HTTP contenant le HTML généré
     */
    public function searchAjax()
    {
        // CORRECTION SÉCURITÉ : Validation et nettoyage du paramètre
        $q = $this->request->getGet('q');
        if ($q !== null) {
            $q = trim(strip_tags($q));
            $q = mb_substr($q, 0, 100);
        }

        $traitements = $this->actUser->getTraitementsAvecFinaliteEtSensibles($q);

        $html = '';

        foreach ($traitements as $t) {
            // CORRECTION SÉCURITÉ : Échappement de TOUTES les données, y compris les attributs
            $donnesSensibles = strtolower(trim($t['DONNEESSENSIBLES']));
            $transfertHorsUE = strtolower(trim($t['TRANSFERT_HORS_UE']));
            
            // Détermination des badges avec données échappées
            $badgeSensibles = ($donnesSensibles === 'oui')
                ? '<span class="badge badge-oui">Oui</span>'
                : '<span class="badge badge-non">Non</span>';

            $badgeTransfert = ($transfertHorsUE === 'oui' || $t['TRANSFERT_HORS_UE'] == 1)
                ? '<span class="badge badge-oui">Oui</span>'
                : '<span class="badge badge-non">Non</span>';

            // CORRECTION XSS : Échappement de tous les attributs data-*
            $dataSensibles = esc($donnesSensibles, 'attr');
            $dataTransferts = esc($transfertHorsUE, 'attr');
            $dataDate = esc($t['DATECREATION'], 'attr');
            
            // Construction de l'URL de manière sécurisée
            $consultUrl = esc(site_url('user/consulter/' . intval($t['REF'])), 'attr');

            $html .= sprintf(
                '<tr class="clickable-row" data-sensibles="%s" data-transferts="%s" data-date="%s">
                    <td onclick="window.location=\'%s\';"><strong>%s</strong></td>
                    <td onclick="window.location=\'%s\';"><code>%s</code></td>
                    <td onclick="window.location=\'%s\';">%s</td>
                    <td onclick="window.location=\'%s\';">%s</td>
                    <td class="finalite" onclick="window.location=\'%s\';">%s</td>
                    <td onclick="window.location=\'%s\';">%s</td>
                    <td onclick="window.location=\'%s\';">%s</td>
                </tr>',
                $dataSensibles,
                $dataTransferts,
                $dataDate,
                $consultUrl,
                esc($t['NOM']),
                $consultUrl,
                esc($t['REF']),
                $consultUrl,
                esc($t['DATECREATION']),
                $consultUrl,
                esc($t['DATEMAJ']),
                $consultUrl,
                esc($t['FINALITE']),
                $consultUrl,
                $badgeSensibles,
                $consultUrl,
                $badgeTransfert
            );
        }

        // CORRECTION SÉCURITÉ : Header Content-Type approprié
        return $this->response
                    ->setHeader('Content-Type', 'text/html; charset=UTF-8')
                    ->setBody($html);
    }

    /**
     * Affiche le formulaire de création d'un nouveau traitement.
     * Charge toutes les données de référence nécessaires (catégories, types, etc.).
     *
     * @return string Vue du formulaire de création
     */
    public function creer()
    {
        $model = new ActionPageInfo();
        $data = $this->loadCommonData($model);
        $data['identite'] = $this->data['identite'];
        $data['mode'] = 'create';
        $data['traitement'] = null;

        return view('user/v_user_traitement_form', $data);
    }

    /**
     * Affiche le détail complet d'un traitement en mode lecture seule.
     * Enregistre la consultation dans les logs.
     *
     * @param int $ref Référence du traitement à consulter
     * @return string Vue détaillée du traitement
     * @throws \CodeIgniter\Exceptions\PageNotFoundException Si le traitement n'existe pas
     */
    public function consulter($ref)
    {
        // CORRECTION SÉCURITÉ : Validation du paramètre
        $ref = intval($ref);
        if ($ref <= 0) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Référence invalide");
        }

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

    /**
     * Enregistre un nouveau traitement avec tous ses blocs associés.
     * Traite les données du formulaire et insère le traitement principal et ses éléments liés.
     * CORRECTION SÉCURITÉ : Gestion d'erreurs et validation améliorées.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers le tableau avec message de succès
     */
    public function save()
    {
        // CORRECTION SÉCURITÉ : Validation CSRF
        if (!$this->validate(['csrf_test_name' => 'required'], 
            ['csrf_test_name' => ['required' => 'Token CSRF invalide']]
        )) {
            return redirect()->back()->with('error', 'Erreur de sécurité : token invalide');
        }

        $model = new ActionPageInfo();
        
        try {
            // CORRECTION SÉCURITÉ : Nettoyage des données
            $nom = trim($this->request->getPost('nom') ?? '');
            if (empty($nom)) {
                throw new \RuntimeException("Le nom du traitement est obligatoire");
            }

            $traitementData = [
                'NOM'            => $nom,
                'DATECREATION'   => date('Y-m-d'),
                'DATEMAJ'        => date('Y-m-d'),
                'TRANSFERTHHORSUE' => $this->request->getPost('checkboxTransfert') ? 1 : 0,
            ];

            $ref = $model->insertTraitement($traitementData);

            if (!$ref) {
                throw new \RuntimeException("Erreur lors de la création du traitement");
            }

            // Sauvegarde des blocs avec gestion d'erreurs
            $this->saveBlocs($model, $ref);

            return redirect()->to('/user/tableau')->with('success', 'Traitement créé avec succès');

        } catch (\RuntimeException $e) {
            // CORRECTION SÉCURITÉ : Ne pas exposer les détails techniques à l'utilisateur
            log_message('error', 'Erreur création traitement : ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la sauvegarde : ' . esc($e->getMessage()));
        } catch (\Exception $e) {
            log_message('error', 'Erreur inattendue création traitement : ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Une erreur inattendue s\'est produite. Veuillez réessayer.');
        }
    }

    /**
     * Enregistre tous les blocs dynamiques associés à un traitement.
     * Traite les acteurs, finalités, catégories DCP, données sensibles, personnes concernées,
     * destinataires, mesures de sécurité et transferts hors UE.
     * CORRECTION SÉCURITÉ : Try-catch pour chaque type de bloc.
     *
     * @param ActionPageInfo $model Modèle d'accès aux données
     * @param int $ref Référence du traitement
     * @return void
     * @throws \RuntimeException En cas d'erreur lors de la sauvegarde
     */
    private function saveBlocs($model, $ref)
    {
        // Acteurs
        $noms = $this->request->getPost('acteur_nom');
        if ($noms && is_array($noms)) {
            foreach ($noms as $i => $nom) {
                if (trim($nom) === '') continue;

                try {
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
                    
                    if ($idActeur) {
                        $model->linkActeurToTraitement($idActeur, $ref);
                    }
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion acteur : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'un acteur : " . $e->getMessage());
                }
            }
        }

        // Finalités
        $finalites = $this->request->getPost('finalite');
        if ($finalites && is_array($finalites)) {
            foreach ($finalites as $i => $libelle) {
                if (trim($libelle) === '') continue;

                try {
                    $model->insertFinalite([
                        'REF'          => $ref,
                        'LIBELLE'      => $libelle,
                        'ESTPRINCIPAL' => isset($this->request->getPost('est_principal')[$i]) ? 1 : 0,
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion finalité : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'une finalité");
                }
            }
        }

        // Catégories DCP
        $catDesc = $this->request->getPost('categorie_description');
        if ($catDesc && is_array($catDesc)) {
            foreach ($catDesc as $i => $desc) {
                $idCateg = $this->request->getPost('categorie_type')[$i] ?? null;
                if (trim($desc) === '' || !$idCateg) continue;

                try {
                    $model->insertCategorie([
                        'REF'                => $ref,
                        'DESCRIPTION'        => $desc,
                        'DUREECONSERVATION'  => $this->request->getPost('categorie_duree')[$i] ?? '',
                        'IDCATEG'            => $idCateg,
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion catégorie : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'une catégorie");
                }
            }
        }

        // Données sensibles
        $sensDesc = $this->request->getPost('sensible_description');
        if ($sensDesc && is_array($sensDesc)) {
            foreach ($sensDesc as $i => $desc) {
                $idCateg = $this->request->getPost('sensible_categorie')[$i] ?? null;
                if (trim($desc) === '' || !$idCateg) continue;

                try {
                    $model->insertSensible([
                        'REF'                => $ref,
                        'DESCRIPTION'        => $desc,
                        'DUREECONSERVATION'  => $this->request->getPost('sensible_duree')[$i] ?? '',
                        'IDCATEG'            => $idCateg,
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion donnée sensible : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'une donnée sensible");
                }
            }
        }

        // Personnes concernées
        $persDesc = $this->request->getPost('personne_description');
        if ($persDesc && is_array($persDesc)) {
            foreach ($persDesc as $i => $idCat) {
                if (!$idCat) continue;

                try {
                    $model->insertPersonne([
                        'REF'        => $ref,
                        'ID_EST_DE_CATEGORIE_PERSONNE' => $idCat,
                        'PRECIS'     => $this->request->getPost('personne_precision')[$i] ?? '',
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion personne : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'une personne concernée");
                }
            }
        }

        // Destinataires
        $destDesc = $this->request->getPost('destinataire_description');
        if ($destDesc && is_array($destDesc)) {
            foreach ($destDesc as $i => $idType) {
                if (!$idType) continue;

                try {
                    $model->insertDestinataire([
                        'REF'        => $ref,
                        'ID_EST_DE_TYPE_DESTINATAIRE' => $idType,
                        'PRECIS'     => $this->request->getPost('destinataire_precision')[$i] ?? '',
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion destinataire : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'un destinataire");
                }
            }
        }

        // Mesures de sécurité
        $secDesc = $this->request->getPost('securite_description');
        if ($secDesc && is_array($secDesc)) {
            foreach ($secDesc as $i => $idType) {
                if (!$idType) continue;

                try {
                    $model->insertSecurite([
                        'REF'        => $ref,
                        'ID_EST_DE_TYPE_DE_MESURE' => $idType,
                        'PRECIS'     => $this->request->getPost('securite_precision')[$i] ?? '',
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion mesure sécurité : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'une mesure de sécurité");
                }
            }
        }

        // Transferts hors UE
        $transDest = $this->request->getPost('transfert_destinataire');
        if ($transDest && is_array($transDest)) {
            foreach ($transDest as $i => $dest) {
                $paysId     = $this->request->getPost('transfert_pays')[$i] ?? null;
                $garantieId = $this->request->getPost('transfert_garantie')[$i] ?? null;
                if (trim($dest) === '' || !$paysId || !$garantieId) continue;

                try {
                    $model->insertTransfert([
                        'REF'                     => $ref,
                        'DESTINATAIRE'            => $dest,
                        'ID_TRANSFERT_VERS_PAYS'  => $paysId,
                        'ID_GARANTIE_APPLIQUEE'   => $garantieId,
                        'LIENDOC'                 => $this->request->getPost('transfert_lien')[$i] ?? '',
                    ]);
                } catch (\RuntimeException $e) {
                    log_message('error', "Erreur insertion transfert : " . $e->getMessage());
                    throw new \RuntimeException("Erreur lors de l'ajout d'un transfert hors UE");
                }
            }
        }
    }

    /**
     * Charge toutes les données de référence nécessaires pour les formulaires.
     * Récupère les listes de catégories, types et autres données pour les sélecteurs.
     *
     * @param ActionPageInfo $model Modèle d'accès aux données
     * @return array Tableau associatif contenant toutes les listes de référence
     */
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

    /**
     * Enregistre dans les logs la consultation de la liste des traitements.
     *
     * @return void
     */
    private function logConsultationListe()
    {
        $this->actUser->logAction('CONSULTATION_LISTE', 'Consultation de la liste des traitements');
    }

    /**
     * Enregistre dans les logs la consultation d'un traitement spécifique.
     *
     * @param int $ref Référence du traitement consulté
     * @return void
     */
    private function logConsultationTraitement($ref)
    {
        $this->actUser->logAction('CONSULTATION', "Consultation du traitement $ref");
    }
}