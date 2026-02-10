<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Anonyme::index');
$routes->get('/anonyme', 'Anonyme::index');
$routes->post('/anonyme/seConnecter', 'Anonyme::seConnecter');



// Accessible uniquement avec DROIT = 'AD'
$routes->group('rssi', function($routes) {
    // Dashboard
    $routes->get('/', 'Rssi::index');
    
    // Déconnexion
    $routes->get('seDeconnecter', 'Rssi::seDeconnecter');
    
    // Gestion des traitements
    $routes->get('tableau', 'Rssi::tableau');
    $routes->get('searchAjax', 'Rssi::searchAjax');
    
    // Création/Édition de traitement
    $routes->get('create', 'Rssi::create');
    $routes->get('edit/(:num)', 'Rssi::edit/$1');
    $routes->post('save', 'Rssi::save');
    
    // Logs système
    $routes->get('logs', 'Rssi::logs');
    
    // Export PDF
    $routes->post('genererPDF', 'Rssi::genererPDF');
});


// Accessible uniquement avec DROIT = 'US'
$routes->group('user', function($routes) {
    // Dashboard
    $routes->get('/', 'User::index');
    
    // Déconnexion
    $routes->get('seDeconnecter', 'User::seDeconnecter');
    
    // Consultation des traitements (lecture seule)
    $routes->get('tableau', 'User::tableau');
    $routes->get('searchAjax', 'User::searchAjax');
    $routes->get('consulter/(:num)', 'User::consulter/$1');
    
    // Création de traitement (si autorisé)
    $routes->get('creer', 'User::creer');
    $routes->post('save', 'User::save');
});



// ROUTES DE TEST (désactiver en production)
if (ENVIRONMENT !== 'production') {
    $routes->get('jeu-test/generer', 'JeuTest::generer');
}