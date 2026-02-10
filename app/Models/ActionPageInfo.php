<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ActionPageInfo extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        
        $userId = session()->get('ID');
        if ($userId) {
            $this->db->query("SET @user_id = " . intval($userId));
        }
    }

    /* ---------------------------------------------------------
       LISTES POUR LES SELECTS
    --------------------------------------------------------- */

    public function getCategDCP() {
        return $this->db->table('CATEGDCP')->get()->getResult();
    }

    public function getCategDCPSensible() {
        return $this->db->table('CATEGDCPSENSIBLE')->get()->getResult();
    }

    public function getPersonnesConcerne() {
        return $this->db->table('CATEGPERSONNECONCERNE')->get()->getResult();
    }

    public function getTypeActeur() {
        return $this->db->table('TYPEACTEUR')->get()->getResult();
    }

    public function getTypeMesureSecurite() {
        return $this->db->table('TYPEMESURESECURITE')->get()->getResult();
    }

    public function getTypeDestinataire() {
        return $this->db->table('TYPEDESTINATAIRE')->get()->getResult();
    }

    public function getTypeGarantie() {
        return $this->db->table('TYPEDEGARANTIE')->get()->getResult();
    }

    public function getPays() {
        return $this->db->table('PAYS')->get()->getResult();
    }

    /* ---------------------------------------------------------
       TRAITEMENT PRINCIPAL
    --------------------------------------------------------- */

    public function getTraitementByRef($ref) {
        return $this->db->table('TRAITEMENT')
                        ->where('REF', $ref)
                        ->get()
                        ->getRowArray();
    }

    public function insertTraitement($data) {
        $this->db->table('TRAITEMENT')->insert($data);
        return $this->db->insertID(); // Retourner l'ID auto-généré
    }

    public function updateTraitement($ref, $data) {
        return $this->db->table('TRAITEMENT')
                        ->where('REF', $ref)
                        ->update($data);
    }

    /* ---------------------------------------------------------
       SUPPRESSION DES BLOCS LIES
    --------------------------------------------------------- */

    public function deleteAllBlocs($ref) {
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

        foreach ($tables as $t) {
            $this->db->table($t)->where('REF', $ref)->delete();
        }
    }

    /* ---------------------------------------------------------
       INSERT DES BLOCS DYNAMIQUES
    --------------------------------------------------------- */

    public function insertActeur($data) {
        $this->db->table('ACTEURS')->insert($data);
        return $this->db->insertID();
    }

    public function linkActeurToTraitement($idActeur, $ref) {
        return $this->db->table('IMPLIQUE_PAR_ACTEUR')->insert([
            'IDACTEUR' => $idActeur,
            'REF'      => $ref
        ]);
    }

    public function insertFinalite($data) {
        return $this->db->table('FINALITE')->insert($data);
    }

    public function insertCategorie($data) {
        return $this->db->table('LISTEDCP')->insert($data);
    }

    public function insertSensible($data) {
        return $this->db->table('LISTEDCPSENSIBLE')->insert($data);
    }

    public function insertPersonne($data) {
        return $this->db->table('LISTEPERSONNECONCERNE')->insert($data);
    }

    public function insertDestinataire($data) {
        return $this->db->table('LISTEDESTINATAIRE')->insert($data);
    }

    public function insertSecurite($data) {
        return $this->db->table('LISTEMESURESECURITE')->insert($data);
    }

    public function insertTransfert($data) {
        return $this->db->table('TRANSFERTHORSUE')->insert($data);
    }

    /* ---------------------------------------------------------
       RÉCUPÉRATION DES BLOCS POUR PRÉREMPLISSAGE
    --------------------------------------------------------- */

    public function getActeursByTraitement($ref) {
        return $this->db->table('ACTEURS a')
                        ->join('IMPLIQUE_PAR_ACTEUR ipa', 'ipa.IDACTEUR = a.IDACTEUR')
                        ->where('ipa.REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getFinalitesByTraitement($ref) {
        return $this->db->table('FINALITE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getCategoriesByTraitement($ref) {
        return $this->db->table('LISTEDCP')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getSensiblesByTraitement($ref) {
        return $this->db->table('LISTEDCPSENSIBLE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getPersonnesByTraitement($ref) {
        return $this->db->table('LISTEPERSONNECONCERNE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getDestinatairesByTraitement($ref) {
        return $this->db->table('LISTEDESTINATAIRE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getSecuritesByTraitement($ref) {
        return $this->db->table('LISTEMESURESECURITE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getTransfertsByTraitement($ref) {
        return $this->db->table('TRANSFERTHORSUE')
                        ->where('REF', $ref)
                        ->get()
                        ->getResultArray();
    }
}