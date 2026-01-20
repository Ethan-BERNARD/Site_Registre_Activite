<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DataAccess;

/**
 * Logique métier liée aux actions d’un utilisateur authentifié.
 */
class ActionsUtilisateur extends Model
{
    private $dao;
    private $idUtilisateur;

    public function __construct($idUtilisateur)
    {
        parent::__construct();

        $this->dao = new DataAccess();
        $this->idUtilisateur = $idUtilisateur;
    }
}