<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DataAccess;

/**
 * Logique métier liée aux actions du RSSI.
 */
class ActionsRssi extends Model
{
    private $dao;
    private $idRssi;

    public function __construct($idRssi)
    {
        parent::__construct();

        $this->dao = new DataAccess();
        $this->idRssi = $idRssi;
    }
}