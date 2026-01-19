<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;

class Authentif extends Model
{
    private $session;

    /**
     * Constructeur : initialise l'accès à la session
     */
    function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Vérifie si l'utilisateur connecté possède le rôle "rssi"
     * (administrateur du registre des traitements).
     *
     * Retourne true si :
     *   - un utilisateur est connecté (ID présent en session)
     *   - son rôle est exactement "rssi"
     *
     * Retourne false sinon.
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
     * Vérifie si l'utilisateur connecté possède le rôle "utilisateur"
     * (membre de l'équipe éducative avec droits limités).
     *
     * Retourne true si :
     *   - un utilisateur est connecté (ID présent en session)
     *   - son rôle est exactement "utilisateur"
     *
     * Retourne false sinon.
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
     * @param array $authUser Tableau associatif contenant :
     *   - ID
     *   - LOGIN
     *   - DROIT
     */
    public function connecter($authUser)
    {
        $this->session->set('ID',    $authUser['ID']);
        $this->session->set('LOGIN', $authUser['LOGIN']);
        $this->session->set('DROIT', $authUser['DROIT']);
    }

    /**
     * Déconnecte l'utilisateur :
     *   - supprime les variables de session
     *   - détruit la session
     *   - redirige vers la page de connexion
     */
    public function deconnecter()
    {
        $this->session->remove(['ID', 'LOGIN', 'DROIT']);
        $this->session->destroy();

        return redirect()->to('/anonyme');
    }

    /**
     * Vérifie si les identifiants fournis correspondent à un utilisateur existant.
     *
     * @param string $login  Login saisi
     * @param string $mdp    Mot de passe saisi (en clair dans ta base actuelle)
     *
     * @return array|null    Retourne les infos de l'utilisateur si correct,
     *                       sinon null.
     */
    public function authentifier($login, $mdp)
    {
        $dao = new DataAccess();
        $authUser = $dao->getUtilisateur($login);

        // Si aucun utilisateur trouvé OU mot de passe incorrect → échec
        if (empty($authUser) || $authUser['MDP'] != $mdp) {
            return null;
        }

        // On efface le mot de passe avant de renvoyer les données
        $authUser['MDP'] = '';

        return $authUser;
    }
}