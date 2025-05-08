<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/dashboard', 'DashboardController::index');
$routes->group('masters', function(RouteCollection $routes){
    $routes->group('users', function(RouteCollection $routes) {
        $routes->get('', 'UsersController::index');
        $routes->post('', 'UsersController::store');
        $routes->put('(:num)', 'UsersController::update/$1');
        $routes->delete('(:num)', 'UsersController::delete/$1');
    });
});
