<?php namespace App\Controllers;

use App\Models\Authentif;
use App\Models\ActionsUtilisateur;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Utilisateur extends BaseController
{
    private $authentif;
    private $idUtilisateur;
    private $data = [];
    private $actUtilisateur;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->authentif = new Authentif();
        $this->session   = session();

        // 🔐 Vérification de session AVANT tout
        if (!$this->session->get('ID')) {
            redirect()->to('/anonyme')->send();
            exit;
        }

        // 🔒 Anti-cache
        $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
        $this->response->setHeader("Pragma", "no-cache");
        $this->response->setHeader("Expires", "0");

        // ✔ Identité utilisateur
        $this->idUtilisateur = $this->session->get('ID');
        $this->data['identite'] = $this->session->get('LOGIN');

        $this->actUtilisateur = new ActionsUtilisateur($this->idUtilisateur);
    }

    public function index()
    {
        return view('user/v_user_accueil', $this->data);
    }

    public function seDeconnecter()
    {
        return $this->authentif->deconnecter();
    }

    public function documentsInternes()
    {
        return view('user/v_user_documents', $this->data);
    }

    public function communicationsRSSI()
    {
        return view('user/v_user_communications', $this->data);
    }
}