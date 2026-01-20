<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DataAccess;

/**
 * Gestion de l’authentification et des rôles utilisateur.
 */
class Authentif extends Model
{
    /** @var \CodeIgniter\Session\Session */
    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Indique si l'utilisateur connecté est RSSI.
     */
    public function estRssi(): bool
    {
        return $this->session->get('ID') !== null
            && $this->session->get('DROIT') === 'rssi';
    }

    /**
     * Indique si l'utilisateur connecté est un utilisateur standard.
     */
    public function estUtilisateur(): bool
    {
        return $this->session->get('ID') !== null
            && $this->session->get('DROIT') === 'utilisateur';
    }

    /**
     * Enregistre les informations de l'utilisateur en session.
     */
    public function connecter(array $authUser): void
    {
        $this->session->set([
            'ID'    => $authUser['ID'],
            'LOGIN' => $authUser['LOGIN'],
            'DROIT' => $authUser['DROIT']
        ]);
    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     */
    public function deconnecter()
    {
        $this->session->remove(['ID', 'LOGIN', 'DROIT']);
        $this->session->destroy();

        return redirect()->to('/anonyme');
    }

    /**
     * Authentifie un utilisateur via login + mot de passe.
     * Retourne les données utilisateur ou null si échec.
     */
    public function authentifier(string $login, string $mdp): ?array
    {
        $dao = new DataAccess();
        $authUser = $dao->getUtilisateur($login);

        if (empty($authUser)) {
            return null;
        }

        if (!password_verify($mdp, $authUser['MDP'])) {
            return null;
        }

        // On ne renvoie jamais le hash
        $authUser['MDP'] = '';

        return $authUser;
    }

    /**
     * Crée un utilisateur avec hashage automatique du mot de passe.
     */
    public function creerUtilisateur(string $login, string $mdp): bool
    {
        $dao = new DataAccess();
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        return $dao->insertUtilisateur($login, $hash);
    }

    /**
     * Vérification rapide des identifiants (hash déjà connu).
     */
    public function verifierConnexion(string $login, string $mdp): bool
    {
        $dao = new DataAccess();
        $hash = $dao->getHashUtilisateur($login);

        return $hash && password_verify($mdp, $hash);
    }
}