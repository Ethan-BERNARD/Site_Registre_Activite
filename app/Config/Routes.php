<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Anonyme::index');
$routes->get('/anonyme', 'Anonyme::index');
$routes->post('/anonyme/seConnecter', 'Anonyme::seConnecter');

// Routes RSSI
$routes->get('/rssi', 'Rssi::index');
$routes->get('/rssi/seDeconnecter', 'Rssi::seDeconnecter');
$routes->get('/gestionTraitement', 'Rssi::tab');
$routes->get('/logs', 'Rssi::logs');

// Routes Utilisateur
$routes->get('/utilisateur', 'Utilisateur::index');
$routes->get('/utilisateur/seDeconnecter', 'Utilisateur::seDeconnecter');