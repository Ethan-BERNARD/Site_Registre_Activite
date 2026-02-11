<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

/**
 * Gère l'accès aux données pour les formulaires de traitement.
 * Utilise le Query Builder de CodeIgniter pour les opérations CRUD.
 * CORRECTION : Ajout de validation des données avant insertion.
 */
class ActionPageInfo extends Model
{
    /** @var \CodeIgniter\Database\BaseConnection Connexion à la base de données */
    protected $db;

    /**
     * Initialise la connexion à la base de données et configure l'utilisateur courant.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        
        $userId = session()->get('ID');
        if ($userId) {
            // CORRECTION SÉCURITÉ : Utilisation de requête préparée
            $this->db->query("SET @user_id = ?", [intval($userId)]);
        }
    }

    /**
     * Récupère la liste des catégories de données à caractère personnel.
     *
     * @return array Liste des catégories DCP
     */
    public function getCategDCP() {
        return $this->db->table('CATEGDCP')->get()->getResult();
    }

    /**
     * Récupère la liste des catégories de données sensibles.
     *
     * @return array Liste des catégories de données sensibles
     */
    public function getCategDCPSensible() {
        return $this->db->table('CATEGDCPSENSIBLE')->get()->getResult();
    }

    /**
     * Récupère la liste des catégories de personnes concernées.
     *
     * @return array Liste des catégories de personnes concernées
     */
    public function getPersonnesConcerne() {
        return $this->db->table('CATEGPERSONNECONCERNE')->get()->getResult();
    }

    /**
     * Récupère la liste des types d'acteurs (responsable, sous-traitant, etc.).
     *
     * @return array Liste des types d'acteurs
     */
    public function getTypeActeur() {
        return $this->db->table('TYPEACTEUR')->get()->getResult();
    }

    /**
     * Récupère la liste des types de mesures de sécurité.
     *
     * @return array Liste des types de mesures de sécurité
     */
    public function getTypeMesureSecurite() {
        return $this->db->table('TYPEMESURESECURITE')->get()->getResult();
    }

    /**
     * Récupère la liste des types de destinataires.
     *
     * @return array Liste des types de destinataires
     */
    public function getTypeDestinataire() {
        return $this->db->table('TYPEDESTINATAIRE')->get()->getResult();
    }

    /**
     * Récupère la liste des types de garanties pour les transferts hors UE.
     *
     * @return array Liste des types de garanties
     */
    public function getTypeGarantie() {
        return $this->db->table('TYPEDEGARANTIE')->get()->getResult();
    }

    /**
     * Récupère la liste des pays.
     *
     * @return array Liste des pays
     */
    public function getPays() {
        return $this->db->table('PAYS')->get()->getResult();
    }

    /**
     * Récupère un traitement par sa référence.
     *
     * @param int $ref Référence du traitement
     * @return array|null Données du traitement ou null si non trouvé
     */
    public function getTraitementByRef($ref) {
        return $this->db->table('TRAITEMENT')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getRowArray();
    }

    /**
     * NOUVELLE MÉTHODE : Valide les données d'un traitement avant insertion/mise à jour.
     *
     * @param array $data Données à valider
     * @return array Tableau avec 'valid' (bool) et 'errors' (array)
     */
    private function validateTraitementData($data)
    {
        $errors = [];

        // Validation du nom (obligatoire, max 150 caractères)
        if (empty($data['NOM']) || trim($data['NOM']) === '') {
            $errors[] = "Le nom du traitement est obligatoire";
        } elseif (mb_strlen($data['NOM']) > 150) {
            $errors[] = "Le nom du traitement ne peut pas dépasser 150 caractères";
        }

        // Validation TRANSFERTHHORSUE (doit être 0 ou 1)
        if (isset($data['TRANSFERTHHORSUE']) && !in_array($data['TRANSFERTHHORSUE'], [0, 1, '0', '1'], true)) {
            $errors[] = "La valeur de TRANSFERTHHORSUE doit être 0 ou 1";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * NOUVELLE MÉTHODE : Valide les données d'un acteur avant insertion.
     *
     * @param array $data Données à valider
     * @return array Tableau avec 'valid' (bool) et 'errors' (array)
     */
    private function validateActeurData($data)
    {
        $errors = [];

        // Validation du nom (max 40 caractères)
        if (isset($data['NOM']) && mb_strlen($data['NOM']) > 40) {
            $errors[] = "Le nom de l'acteur ne peut pas dépasser 40 caractères";
        }

        // Validation de l'email
        if (!empty($data['MAIL']) && !filter_var($data['MAIL'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email de l'acteur n'est pas valide : " . esc($data['MAIL']);
        }

        // Validation du téléphone (format simple)
        if (!empty($data['TEL']) && !preg_match('/^[\d\s\+\-\(\)\.]+$/', $data['TEL'])) {
            $errors[] = "Le téléphone contient des caractères invalides";
        }

        // Validation longueur des champs
        $lengthLimits = [
            'ADRESSE' => 300,
            'CP' => 10,
            'VILLE' => 150,
            'PAYS' => 150,
            'TEL' => 20,
            'MAIL' => 150
        ];

        foreach ($lengthLimits as $field => $maxLength) {
            if (isset($data[$field]) && mb_strlen($data[$field]) > $maxLength) {
                $errors[] = "Le champ $field ne peut pas dépasser $maxLength caractères";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Insère un nouveau traitement dans la base de données.
     * CORRECTION : Ajout de validation des données.
     *
     * @param array $data Données du traitement à insérer
     * @return int|false ID auto-généré du traitement ou false en cas d'erreur
     * @throws \RuntimeException Si les données sont invalides
     */
    public function insertTraitement($data)
    {
        // Validation des données
        $validation = $this->validateTraitementData($data);
        if (!$validation['valid']) {
            throw new \RuntimeException("Données invalides : " . implode(', ', $validation['errors']));
        }

        // Nettoyage des données
        $data['NOM'] = trim($data['NOM']);
        $data['TRANSFERTHHORSUE'] = isset($data['TRANSFERTHHORSUE']) ? intval($data['TRANSFERTHHORSUE']) : 0;

        $result = $this->db->table('TRAITEMENT')->insert($data);
        
        if (!$result) {
            return false;
        }

        return $this->db->insertID();
    }

    /**
     * Met à jour un traitement existant.
     * CORRECTION : Ajout de validation des données.
     *
     * @param int $ref Référence du traitement à modifier
     * @param array $data Nouvelles données du traitement
     * @return bool True si la mise à jour a réussi
     * @throws \RuntimeException Si les données sont invalides
     */
    public function updateTraitement($ref, $data)
    {
        // Validation des données
        $validation = $this->validateTraitementData($data);
        if (!$validation['valid']) {
            throw new \RuntimeException("Données invalides : " . implode(', ', $validation['errors']));
        }

        // Nettoyage des données
        $data['NOM'] = trim($data['NOM']);
        $data['TRANSFERTHHORSUE'] = isset($data['TRANSFERTHHORSUE']) ? intval($data['TRANSFERTHHORSUE']) : 0;

        return $this->db->table('TRAITEMENT')
                        ->where('REF', intval($ref))
                        ->update($data);
    }

    /**
     * Supprime tous les blocs liés à un traitement (finalités, acteurs, etc.).
     * Utilisé avant une mise à jour complète du traitement.
     * CORRECTION : Transaction pour assurer l'intégrité.
     *
     * @param int $ref Référence du traitement
     * @return void
     * @throws \RuntimeException Si la suppression échoue
     */
    public function deleteAllBlocs($ref)
    {
        $ref = intval($ref);

        $tables = [
            'FINALITE',
            'LISTEDCP',
            'LISTEDCPSENSIBLE',
            'LISTEPERSONNECONCERNE',
            'LISTEDESTINATAIRE',
            'LISTEMESURESECURITE',
            'TRANSFERTHORSUE',
            'IMPLIQUE_PAR_ACTEUR'
        ];

        // CORRECTION SÉCURITÉ : Utilisation d'une transaction
        $this->db->transStart();

        try {
            foreach ($tables as $t) {
                $this->db->table($t)->where('REF', $ref)->delete();
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException("Erreur lors de la suppression des blocs du traitement $ref");
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw new \RuntimeException("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    /**
     * Insère un nouvel acteur.
     * CORRECTION : Ajout de validation des données.
     *
     * @param array $data Données de l'acteur
     * @return int|false ID auto-généré de l'acteur ou false en cas d'erreur
     * @throws \RuntimeException Si les données sont invalides
     */
    public function insertActeur($data)
    {
        // Validation des données
        $validation = $this->validateActeurData($data);
        if (!$validation['valid']) {
            throw new \RuntimeException("Données acteur invalides : " . implode(', ', $validation['errors']));
        }

        // Nettoyage et sécurisation
        $cleanData = [
            'NOM'     => isset($data['NOM']) ? trim($data['NOM']) : '',
            'ADRESSE' => isset($data['ADRESSE']) ? trim($data['ADRESSE']) : '',
            'CP'      => isset($data['CP']) ? trim($data['CP']) : '',
            'VILLE'   => isset($data['VILLE']) ? trim($data['VILLE']) : '',
            'PAYS'    => isset($data['PAYS']) ? trim($data['PAYS']) : '',
            'TEL'     => isset($data['TEL']) ? trim($data['TEL']) : '',
            'MAIL'    => isset($data['MAIL']) ? trim(strtolower($data['MAIL'])) : '',
            'IDTYPE'  => isset($data['IDTYPE']) ? intval($data['IDTYPE']) : null
        ];

        $result = $this->db->table('ACTEURS')->insert($cleanData);
        
        if (!$result) {
            return false;
        }

        return $this->db->insertID();
    }

    /**
     * Lie un acteur à un traitement.
     *
     * @param int $idActeur ID de l'acteur
     * @param int $ref Référence du traitement
     * @return bool True si l'insertion a réussi
     */
    public function linkActeurToTraitement($idActeur, $ref) {
        return $this->db->table('IMPLIQUE_PAR_ACTEUR')->insert([
            'IDACTEUR' => intval($idActeur),
            'REF'      => intval($ref)
        ]);
    }

    /**
     * Insère une finalité pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données de la finalité
     * @return bool True si l'insertion a réussi
     */
    public function insertFinalite($data) {
        // Validation
        if (empty($data['LIBELLE']) || mb_strlen($data['LIBELLE']) > 300) {
            throw new \RuntimeException("Le libellé de la finalité est invalide");
        }

        $cleanData = [
            'REF'          => intval($data['REF']),
            'LIBELLE'      => trim($data['LIBELLE']),
            'ESTPRINCIPAL' => isset($data['ESTPRINCIPAL']) ? intval($data['ESTPRINCIPAL']) : 0
        ];

        return $this->db->table('FINALITE')->insert($cleanData);
    }

    /**
     * Insère une catégorie de données personnelles pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données de la catégorie
     * @return bool True si l'insertion a réussi
     */
    public function insertCategorie($data) {
        // Validation
        if (empty($data['DESCRIPTION']) || mb_strlen($data['DESCRIPTION']) > 300) {
            throw new \RuntimeException("La description de la catégorie est invalide");
        }

        $cleanData = [
            'REF'                => intval($data['REF']),
            'IDCATEG'            => intval($data['IDCATEG']),
            'DESCRIPTION'        => trim($data['DESCRIPTION']),
            'DUREECONSERVATION'  => isset($data['DUREECONSERVATION']) ? intval($data['DUREECONSERVATION']) : null
        ];

        return $this->db->table('LISTEDCP')->insert($cleanData);
    }

    /**
     * Insère une donnée sensible pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données de la donnée sensible
     * @return bool True si l'insertion a réussi
     */
    public function insertSensible($data) {
        // Validation
        if (empty($data['DESCRIPTION']) || mb_strlen($data['DESCRIPTION']) > 300) {
            throw new \RuntimeException("La description de la donnée sensible est invalide");
        }

        $cleanData = [
            'REF'                => intval($data['REF']),
            'IDCATEG'            => intval($data['IDCATEG']),
            'DESCRIPTION'        => trim($data['DESCRIPTION']),
            'DUREECONSERVATION'  => isset($data['DUREECONSERVATION']) ? intval($data['DUREECONSERVATION']) : null
        ];

        return $this->db->table('LISTEDCPSENSIBLE')->insert($cleanData);
    }

    /**
     * Insère une personne concernée pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données de la personne concernée
     * @return bool True si l'insertion a réussi
     */
    public function insertPersonne($data) {
        $cleanData = [
            'REF'        => intval($data['REF']),
            'ID_EST_DE_CATEGORIE_PERSONNE' => intval($data['ID_EST_DE_CATEGORIE_PERSONNE']),
            'PRECIS'     => isset($data['PRECIS']) ? mb_substr(trim($data['PRECIS']), 0, 300) : ''
        ];

        return $this->db->table('LISTEPERSONNECONCERNE')->insert($cleanData);
    }

    /**
     * Insère un destinataire pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données du destinataire
     * @return bool True si l'insertion a réussi
     */
    public function insertDestinataire($data) {
        $cleanData = [
            'REF'        => intval($data['REF']),
            'ID_EST_DE_TYPE_DESTINATAIRE' => intval($data['ID_EST_DE_TYPE_DESTINATAIRE']),
            'PRECIS'     => isset($data['PRECIS']) ? mb_substr(trim($data['PRECIS']), 0, 300) : ''
        ];

        return $this->db->table('LISTEDESTINATAIRE')->insert($cleanData);
    }

    /**
     * Insère une mesure de sécurité pour un traitement.
     * CORRECTION : Validation et nettoyage des données.
     *
     * @param array $data Données de la mesure de sécurité
     * @return bool True si l'insertion a réussi
     */
    public function insertSecurite($data) {
        $cleanData = [
            'REF'        => intval($data['REF']),
            'ID_EST_DE_TYPE_DE_MESURE' => intval($data['ID_EST_DE_TYPE_DE_MESURE']),
            'PRECIS'     => isset($data['PRECIS']) ? mb_substr(trim($data['PRECIS']), 0, 300) : ''
        ];

        return $this->db->table('LISTEMESURESECURITE')->insert($cleanData);
    }

    /**
     * Insère un transfert hors UE pour un traitement.
     * CORRECTION : Validation et nettoyage des données, validation URL.
     *
     * @param array $data Données du transfert
     * @return bool True si l'insertion a réussi
     */
    public function insertTransfert($data) {
        // Validation de l'URL si présente
        if (!empty($data['LIENDOC'])) {
            $url = filter_var($data['LIENDOC'], FILTER_VALIDATE_URL);
            if ($url === false) {
                throw new \RuntimeException("L'URL du document est invalide : " . esc($data['LIENDOC']));
            }
        }

        $cleanData = [
            'REF'                     => intval($data['REF']),
            'ID_TRANSFERT_VERS_PAYS'  => intval($data['ID_TRANSFERT_VERS_PAYS']),
            'ID_GARANTIE_APPLIQUEE'   => intval($data['ID_GARANTIE_APPLIQUEE']),
            'DESTINATAIRE'            => mb_substr(trim($data['DESTINATAIRE']), 0, 300),
            'LIENDOC'                 => isset($data['LIENDOC']) ? mb_substr(trim($data['LIENDOC']), 0, 500) : ''
        ];

        return $this->db->table('TRANSFERTHORSUE')->insert($cleanData);
    }

    /**
     * Récupère tous les acteurs liés à un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des acteurs
     */
    public function getActeursByTraitement($ref) {
        return $this->db->table('ACTEURS a')
                        ->join('IMPLIQUE_PAR_ACTEUR ipa', 'ipa.IDACTEUR = a.IDACTEUR')
                        ->where('ipa.REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère toutes les finalités d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des finalités
     */
    public function getFinalitesByTraitement($ref) {
        return $this->db->table('FINALITE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère toutes les catégories de données personnelles d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des catégories
     */
    public function getCategoriesByTraitement($ref) {
        return $this->db->table('LISTEDCP')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère toutes les données sensibles d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des données sensibles
     */
    public function getSensiblesByTraitement($ref) {
        return $this->db->table('LISTEDCPSENSIBLE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère toutes les personnes concernées d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des personnes concernées
     */
    public function getPersonnesByTraitement($ref) {
        return $this->db->table('LISTEPERSONNECONCERNE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère tous les destinataires d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des destinataires
     */
    public function getDestinatairesByTraitement($ref) {
        return $this->db->table('LISTEDESTINATAIRE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère toutes les mesures de sécurité d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des mesures de sécurité
     */
    public function getSecuritesByTraitement($ref) {
        return $this->db->table('LISTEMESURESECURITE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }

    /**
     * Récupère tous les transferts hors UE d'un traitement.
     *
     * @param int $ref Référence du traitement
     * @return array Liste des transferts
     */
    public function getTransfertsByTraitement($ref) {
        return $this->db->table('TRANSFERTHORSUE')
                        ->where('REF', intval($ref))
                        ->get()
                        ->getResultArray();
    }
}