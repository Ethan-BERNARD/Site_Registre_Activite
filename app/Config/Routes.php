<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Anonyme::index');
$routes->get('/anonyme', 'Anonyme::index');
$routes->post('/anonyme/seConnecter', 'Anonyme::seConnecter');

// ========== Routes RSSI ==========
$routes->get('/rssi', 'Rssi::index');
$routes->get('/rssi/seDeconnecter', 'Rssi::seDeconnecter');
$routes->get('/gestionTraitement', 'Rssi::tableau');
$routes->get('/logs', 'Rssi::logs');
$routes->post('/rssi/genererPDF', 'Rssi::genererPDF');
$routes->get('/gestionTraitement/searchAjax', 'Rssi::searchAjax');

// PageInfo (édition/création) - Utilisé par RSSI
$routes->get('pageInfo', 'PageInfoController::index');
$routes->get('pageInfo/edit/(:segment)', 'Rssi::edit/$1');
$routes->post('pageInfo/save', 'Rssi::save');

// ========== Routes USER ==========
$routes->get('/user', 'User::index');
$routes->get('/user/seDeconnecter', 'User::seDeconnecter');
$routes->get('/user/tableau', 'User::tableau');
$routes->get('/user/creer', 'User::creer');
$routes->get('/user/consulter/(:segment)', 'User::consulter/$1');
$routes->post('/user/save', 'User::save');
$routes->get('/user/searchAjax', 'User::searchAjax');

// Jeu de test (http://registre.local:8080/jeu-test/generer)
$routes->get('jeu-test/generer', 'JeuTest::generer');