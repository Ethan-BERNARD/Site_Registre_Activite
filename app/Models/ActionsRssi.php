<?php namespace App\Models;

use App\Models\DataAccess;

class ActionsRssi
{
    private $dao;
    private $idRssi;

    public function __construct($idRssi)
    {
        $this->dao = new DataAccess();
        $this->idRssi = $idRssi;
    }

    public function getAllTraitements()
    {
        return $this->dao->getAllTraitements();
    }

    public function getTraitementById($id)
    {
        return $this->dao->getTraitementById($id);
    }

    public function getLogs()
    {
        return $this->dao->getLogs();
    }

    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        return $this->dao->getTraitementsAvecFinaliteEtSensibles($search);
    }
}