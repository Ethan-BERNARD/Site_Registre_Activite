<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;

/**
 * Classe de gestion de l'authentification des utilisateurs.
 */
class Authentif extends Model
{
    /** @var \CodeIgniter\Session\Session */
    private $session;

    /**
     * Constructeur : initialise l'accès à la session
     */
    public function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Vérifie si l'utilisateur connecté possède le rôle "rssi".
     *
     * @return bool
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
     * Vérifie si l'utilisateur connecté possède le rôle "utilisateur".
     *
     * @return bool
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
     * Enregistre dans la session les informations de l'utilisateur connecté.
     *
     * @param array $authUser Tableau associatif : ID, LOGIN, DROIT
     */
    public function connecter($authUser)
    {
        $this->session->set('ID',    $authUser['ID']);
        $this->session->set('LOGIN', $authUser['LOGIN']);
        $this->session->set('DROIT', $authUser['DROIT']);
    }

    /**
     * Déconnecte l'utilisateur et redirige vers la page de connexion.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deconnecter()
    {
        $this->session->remove(['ID', 'LOGIN', 'DROIT']);
        $this->session->destroy();

        return redirect()->to('/anonyme');
    }

    /**
     * Authentifie un utilisateur à partir de son login et mot de passe.
     * Ici on suppose que les mots de passe en BDD sont DÉJÀ hashés.
     *
     * @param string $login Login saisi
     * @param string $mdp   Mot de passe saisi (en clair)
     *
     * @return array|null   Données utilisateur si OK, sinon null
     */
    public function authentifier($login, $mdp)
    {
        $dao = new DataAccess();
        $authUser = $dao->getUtilisateur($login);

        // Aucun utilisateur trouvé → échec
        if (empty($authUser)) {
            return null;
        }

        // Mot de passe hashé stocké en BDD (colonne MDP)
        $hashBDD = $authUser['MDP'];

        // Vérification du mot de passe saisi par rapport au hash
        if (!password_verify($mdp, $hashBDD)) {
            return null;
        }

        // On efface le mot de passe avant de renvoyer les données
        $authUser['MDP'] = '';

        return $authUser;
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     * en hashant automatiquement son mot de passe.
     *
     * @param string $login  Le nom d'utilisateur choisi
     * @param string $mdp    Le mot de passe en clair (fourni par l'utilisateur)
     * @return bool          true si l'insertion réussit, false sinon
     */
    public function creerUtilisateur($login, $mdp)
    {
        // Hash sécurisé du mot de passe
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        // Insertion dans la table "utilisateur"
        return $this->db->table('utilisateur')->insert([
            'LOGIN' => $login,   // identifiant de connexion
            'MDP'   => $hash     // mot de passe hashé
        ]);
    }

    /**
     * Vérifie si un utilisateur peut se connecter (version simple).
     * À n'utiliser que si tu sais que tous les mots de passe sont hashés.
     *
     * @param string $login  Le login saisi dans le formulaire
     * @param string $mdp    Le mot de passe saisi (en clair)
     * @return bool          true si la connexion est valide, false sinon
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