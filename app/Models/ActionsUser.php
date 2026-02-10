<?php namespace App\Models;

use App\Models\DataAccess;

class ActionsUser
{
    private $dao;
    private $idUser;

    public function __construct($idUser)
    {
        $this->idUser = $idUser;
        $this->dao = new DataAccess($idUser);
    }

    public function getTraitementsAvecFinaliteEtSensibles($search = null)
    {
        return $this->dao->getTraitementsAvecFinaliteEtSensibles($search);
    }

    public function getTraitementById($id)
    {
        return $this->dao->getTraitementById($id);
    }

    public function logAction($typeAction, $details)
    {
        $this->dao->enregistrerLog(
            $this->idUser,
            $typeAction,
            $details
        );
    }

    public function getDashboardStats()
    {
        return $this->dao->getDashboardStats();
    }
}