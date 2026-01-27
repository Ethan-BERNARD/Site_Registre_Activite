<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/page-info', 'PageInfoController::index');
$routes->post('pageInfo/save', 'PageInfoController::save');


