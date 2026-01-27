<?php namespace App\Controllers;

use App\Models\Authentif;

/**
 * Contrôleur public : gestion de la connexion et redirection selon le rôle.
 */
class Anonyme extends BaseController
{
    /**
     * Page d’accueil publique.
     * Redirige automatiquement si l’utilisateur est déjà connecté.
     */
    public function index()
    {
        $authentif = new Authentif();

        if ($authentif->estRssi()) {
            return redirect()->to('/rssi');
        }

        if ($authentif->estUtilisateur()) {
            return redirect()->to('/utilisateur');
        }

        return $this->login();
    }

    /**
     * Affiche le formulaire de connexion.
     *
     * @param string|null $errMsg Message d’erreur éventuel.
     */
    public function login($errMsg = null)
    {
        return view('v_auth_connexion', ['erreur' => $errMsg]);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function seConnecter()
    {
        $login = $this->request->getPost('LOGIN');
        $mdp   = $this->request->getPost('MDP');

        $authentif = new Authentif();
        $authUser  = $authentif->authentifier($login, $mdp);

        if (empty($authUser)) {
            return $this->login('Login ou mot de passe incorrect');
        }

        $authentif->connecter($authUser);
        return $this->index();
    }
}