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

    public function getTraitementById($ref) {
        return $this->db->table('TRAITEMENT')
                        ->where('REF', $ref)
                        ->get()
                        ->getRowArray();
    }

    public function insertTraitement($data) {
        // REF doit être fourni dans $data
        $this->db->table('TRAITEMENT')->insert($data);
        return $data['REF'];
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
            'ACTEURS',
            'FINALITE',
            'LISTEDCP',
            'LISTEDCPSENSIBLE',
            'LISTEPERSONNECONCERNE',
            'LISTEDESTINATAIRE',
            'LISTEMESURESECURITE',
            'TRANSFERTHORSUE'
        ];

        foreach ($tables as $t) {
            $this->db->table($t)->where('REFTRAITEMENT', $ref)->delete();
        }
    }

    /* ---------------------------------------------------------
       INSERT DES BLOCS DYNAMIQUES
    --------------------------------------------------------- */

    public function insertActeur($data) {
        return $this->db->table('ACTEURS')->insert($data);
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
        return $this->db->table('ACTEURS')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getFinalitesByTraitement($ref) {
        return $this->db->table('FINALITE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getCategoriesByTraitement($ref) {
        return $this->db->table('LISTEDCP')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getSensiblesByTraitement($ref) {
        return $this->db->table('LISTEDCPSENSIBLE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getPersonnesByTraitement($ref) {
        return $this->db->table('LISTEPERSONNECONCERNE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getDestinatairesByTraitement($ref) {
        return $this->db->table('LISTEDESTINATAIRE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getSecuritesByTraitement($ref) {
        return $this->db->table('LISTEMESURESECURITE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }

    public function getTransfertsByTraitement($ref) {
        return $this->db->table('TRANSFERTHORSUE')
                        ->where('REFTRAITEMENT', $ref)
                        ->get()
                        ->getResultArray();
    }
}