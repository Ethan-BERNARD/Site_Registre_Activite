<?php namespace App\Models;

use App\Models\DataAccess;

class ActionsRssi
{
    private $dao;
    private $idRssi;

    public function __construct($idRssi)
    {
        $this->idRssi = $idRssi;
        $this->dao = new DataAccess($idRssi);
    }

    public function getAllTraitements()
    {
        return $this->dao->getAllTraitements();
    }

    public function getTraitementById($id)
    {
        return $this->dao->getTraitementById($id);
    }

    public function getLogs($limit = 50)
    {
        return $this->dao->getLogs($limit);
    }

    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        return $this->dao->getTraitementsAvecFinaliteEtSensibles($search);
    }

    public function logAction($typeAction, $details)
    {
        $this->dao->enregistrerLog(
            $typeAction,
            $this->idRssi,
            $details
        );
    }
}