<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::index');
$routes->get('/dashboard', 'DashboardController::index');
$routes->group('masters', function(RouteCollection $routes){
    $routes->group('users', function(RouteCollection $routes) {
        $routes->get('', 'UsersController::index');
        $routes->post('', 'UsersController::store');
        $routes->put('(:num)', 'UsersController::update/$1');
        $routes->delete('(:num)', 'UsersController::delete/$1');
    });
});

$routes->group('proposals', function(RouteCollection $routes) {
    $routes->get('', 'ProposalsController::index');
    $routes->get('(:num)/file', 'ProposalsController::getFile/$1');
    $routes->post('(:num)/approval', 'ProposalsController::approval/$1');
});

$routes->group('final-reports', function(RouteCollection $routes) {
    $routes->get('', 'FinalReportsController::index');
    $routes->get('(:num)/file', 'FinalReportsController::getFile/$1');
    $routes->post('(:num)/approval', 'FinalReportsController::approval/$1');
});
