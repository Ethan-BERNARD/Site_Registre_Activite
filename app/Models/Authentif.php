<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DataAccess;

/**
 * Gestion de l'authentification et des rôles utilisateur.
 */
class Authentif extends Model
{
    /** @var \CodeIgniter\Session\Session Session utilisateur */
    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Vérifie si l'utilisateur connecté possède le rôle RSSI (Administrateur).
     *
     * @return bool True si l'utilisateur est RSSI, false sinon
     */
    public function estRssi(): bool
    {
        return $this->session->get('ID') !== null
            && $this->session->get('DROIT') === 'AD';
    }

    /**
     * Vérifie si l'utilisateur connecté possède le rôle utilisateur standard.
     *
     * @return bool True si l'utilisateur est un utilisateur standard, false sinon
     */
    public function estUtilisateur(): bool
    {
        return $this->session->get('ID') !== null
            && $this->session->get('DROIT') === 'US';
    }

    /**
     * Enregistre les informations de l'utilisateur en session après authentification réussie.
     *
     * @param array $authUser Tableau contenant les informations utilisateur (ID, LOGIN, DROIT)
     * @return void
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
     * Redirige vers la page de connexion.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers /anonyme
     */
    public function deconnecter()
    {
        $this->session->remove(['ID', 'LOGIN', 'DROIT']);
        $this->session->destroy();

        return redirect()->to('/anonyme');
    }

    /**
     * Authentifie un utilisateur via login et mot de passe.
     * Vérifie les credentials et retourne les données utilisateur si valides.
     *
     * @param string $login Login de l'utilisateur
     * @param string $mdp Mot de passe en clair
     * @return array|null Données utilisateur (sans le hash du mot de passe) ou null si échec
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

        $authUser['MDP'] = '';

        return $authUser;
    }

    /**
     * Crée un nouvel utilisateur avec hashage automatique du mot de passe.
     *
     * @param string $login Login du nouvel utilisateur
     * @param string $mdp Mot de passe en clair (sera hashé automatiquement)
     * @return bool True si la création a réussi, false sinon
     */
    public function creerUtilisateur(string $login, string $mdp): bool
    {
        $dao = new DataAccess();
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        return $dao->insertUtilisateur($login, $hash);
    }

    /**
     * Vérifie rapidement les identifiants d'un utilisateur.
     * Utile pour les vérifications de connexion sans récupérer toutes les données utilisateur.
     *
     * @param string $login Login de l'utilisateur
     * @param string $mdp Mot de passe en clair
     * @return bool True si les identifiants sont valides, false sinon
     */
    public function verifierConnexion(string $login, string $mdp): bool
    {
        $dao = new DataAccess();
        $hash = $dao->getHashUtilisateur($login);

        return $hash && password_verify($mdp, $hash);
    }
}