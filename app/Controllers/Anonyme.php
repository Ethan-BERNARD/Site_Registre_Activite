<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Authentif;

/**
 * Contrôleur d’accès public (non authentifié).
 *
 * Il gère :
 * - l’affichage du formulaire de connexion
 * - la soumission du formulaire
 * - la redirection automatique vers l’espace correspondant au rôle
 *   (RSSI ou Utilisateur) si l’utilisateur est déjà connecté.
 *
 * Ce contrôleur est le point d’entrée de l’application pour tout visiteur
 * non authentifié.
 */
class Anonyme extends BaseController
{
    /**
     * Page d’accueil publique.
     *
     * Si l’utilisateur est déjà authentifié, il est automatiquement redirigé
     * vers l’espace correspondant à son rôle :
     *   - RSSI → /rssi
     *   - Utilisateur → /utilisateur
     *
     * Sinon, le formulaire de connexion est affiché.
     */
    public function index()
    {
        $authentif = new Authentif();

        if ($authentif->estRssi() === true) {
            return redirect()->to('/rssi');
        }
        elseif ($authentif->estUtilisateur() === true) {
            return redirect()->to('/utilisateur');
        }
        else {
            return $this->login();
        }
    }

    /**
     * Affiche le formulaire de connexion.
     *
     * Un message d’erreur peut être transmis (ex : identifiants incorrects).
     *
     * @param string|null $errMsg Message d’erreur optionnel
     */
    public function login($errMsg = null)
    {
        $data = ['erreur' => $errMsg];
        return view('v_connexion', $data);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     *
     * Étapes :
     *  - récupération du login et du mot de passe postés
     *  - vérification des identifiants via le modèle Authentif
     *  - si échec → retour au formulaire avec message d’erreur
     *  - si succès → enregistrement en session puis redirection
     *    vers la méthode index(), qui redirigera selon le rôle
     */
    public function seConnecter()
    {
        // Récupération des données du formulaire
        $login = $this->request->getPost('LOGIN');
        $mdp   = $this->request->getPost('MDP');

        // Vérification des identifiants
        $authentif = new Authentif();
        $authUser  = $authentif->authentifier($login, $mdp);

        if (empty($authUser)) {
            return $this->login('Login ou mot de passe incorrect');
        } else {
            // Connexion réussie → enregistrement en session
            $authentif->connecter($authUser);

            // Redirection automatique selon le rôle
            return $this->index();
        }
    }
}