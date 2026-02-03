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
$routes->get('/gestionTraitement', 'Rssi::tableau');
$routes->get('/logs', 'Rssi::logs');
$routes->get('/rssi/exportPDF', 'Rssi::exportPDF');
$routes->post('/rssi/genererPDF', 'Rssi::genererPDF');
$routes->get('/gestionTraitement/detail/(:segment)', 'Rssi::detail/$1');

$routes->get('/gestionTraitement/searchAjax', 'Rssi::searchAjax');

$routes->get('/page-info', 'PageInfoController::index'); 
$routes->post('pageInfo/save', 'PageInfoController::save');

// Routes Utilisateur
$routes->get('/user', 'User::index');
$routes->get('/user/seDeconnecter', 'User::seDeconnecter');
$routes->get('/user/documents', 'User::documentsInternes');
$routes->get('/user/communications', 'User::communicationsRSSI');


$routes->get('rssi/testUpdate', 'Rssi::testUpdate');
