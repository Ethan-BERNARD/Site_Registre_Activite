<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DataAccess;

/**
 * Modèle de gestion de l’authentification.
 *
 * Cette classe centralise :
 * - la vérification des identifiants
 * - la gestion des rôles (RSSI / Utilisateur)
 * - l’écriture et la suppression des données de session
 * - la création d’utilisateurs (avec hashage automatique)
 *
 * Elle constitue le cœur du système d’authentification de l’application.
 */
class Authentif extends Model
{
    /** @var \CodeIgniter\Session\Session Instance de la session utilisateur */
    private $session;

    /**
     * Constructeur : initialise l’accès à la session.
     */
    public function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Vérifie si l’utilisateur connecté possède le rôle RSSI.
     *
     * @return bool true si l’utilisateur est RSSI, false sinon
     */
    public function estRssi()
    {
        $id   = $this->session->get('ID');
        $role = $this->session->get('DROIT');

        if ($id === null) {
            return false;
        }

        return $role === 'rssi';
    }

    /**
     * Vérifie si l’utilisateur connecté possède le rôle Utilisateur.
     *
     * @return bool true si l’utilisateur est un utilisateur standard, false sinon
     */
    public function estUtilisateur()
    {
        $id   = $this->session->get('ID');
        $role = $this->session->get('DROIT');

        if ($id === null) {
            return false;
        }

        return $role === 'utilisateur';
    }

    /**
     * Enregistre dans la session les informations de l’utilisateur authentifié.
     *
     * @param array $authUser Tableau associatif contenant : ID, LOGIN, DROIT
     */
    public function connecter($authUser)
    {
        $this->session->set('ID',    $authUser['ID']);
        $this->session->set('LOGIN', $authUser['LOGIN']);
        $this->session->set('DROIT', $authUser['DROIT']);
    }

    /**
     * Déconnecte l’utilisateur et détruit la session.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirection vers la page de connexion
     */
    public function deconnecter()
    {
        $this->session->remove(['ID', 'LOGIN', 'DROIT']);
        $this->session->destroy();

        return redirect()->to('/anonyme');
    }

    /**
     * Authentifie un utilisateur à partir de son login et mot de passe.
     *
     * Les mots de passe en base doivent être préalablement hashés.
     *
     * @param string $login Identifiant saisi
     * @param string $mdp   Mot de passe saisi (en clair)
     *
     * @return array|null Données utilisateur si authentification réussie, sinon null
     */
    public function authentifier($login, $mdp)
    {
        $dao = new DataAccess();
        $authUser = $dao->getUtilisateur($login);

        // Aucun utilisateur correspondant
        if (empty($authUser)) {
            return null;
        }

        // Mot de passe hashé stocké en base
        $hashBDD = $authUser['MDP'];

        // Vérification du mot de passe
        if (!password_verify($mdp, $hashBDD)) {
            return null;
        }

        // On retire le hash avant de renvoyer les données
        $authUser['MDP'] = '';

        return $authUser;
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     * en hashant automatiquement son mot de passe.
     *
     * @param string $login Identifiant choisi
     * @param string $mdp   Mot de passe en clair
     *
     * @return bool true si l’insertion réussit, false sinon
     */
    public function creerUtilisateur($login, $mdp)
    {
        // Hash sécurisé du mot de passe
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        // Insertion dans la table utilisateur
        return $this->db->table('utilisateur')->insert([
            'LOGIN' => $login,
            'MDP'   => $hash
        ]);
    }

    /**
     * Vérifie rapidement si un utilisateur peut se connecter.
     *
     * Méthode alternative simplifiée, à utiliser uniquement si
     * l’on sait que tous les mots de passe en base sont hashés.
     *
     * @param string $login Identifiant saisi
     * @param string $mdp   Mot de passe en clair
     *
     * @return bool true si les identifiants sont valides, false sinon
     */
    public function verifierConnexion($login, $mdp)
    {
        $user = $this->db->table('utilisateur')
                         ->where('LOGIN', $login)
                         ->get()
                         ->getRow();

        if (!$user) {
            return false;
        }

        return password_verify($mdp, $user->MDP);
    }
}