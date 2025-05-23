<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LandingController::index');
$routes->get('/login', 'AuthController::index');
$routes->get('/register', 'AuthController::showRegister');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->post('/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'DashboardController::index');
    $routes->get('/profile', 'ProfileController::index');
    $routes->post('/change-password', 'AuthController::changePassword');
    
    $routes->group('masters', function(RouteCollection $routes) {
        $routes->group('users', function(RouteCollection $routes) {
            $routes->get('', 'UsersController::index');
            $routes->post('', 'UsersController::store');
            $routes->put('(:num)', 'UsersController::update/$1');
            $routes->delete('(:num)', 'UsersController::delete/$1');
        });
    });
    
    $routes->group('proposals', function(RouteCollection $routes) {
        $routes->get('', 'ProposalsController::index');
        $routes->post('', 'ProposalsController::store');
        
        $routes->get('(:num)/file', 'ProposalsController::getFile/$1');
        $routes->post('(:num)/approval', 'ProposalsController::approval/$1');
        
        $routes->delete('(:num)', 'ProposalsController::delete/$1');
    });
    
    $routes->group('final-reports', function(RouteCollection $routes) {
        $routes->get('', 'FinalReportsController::index');
        $routes->post('', 'FinalReportsController::store');
        
        $routes->post('(:num)/certificate', 'FinalReportsController::issueCertificate/$1');
        $routes->get('(:num)/user/(:num)/certificate', 'FinalReportsController::getCertificateFile/$1/$2');
        
        $routes->get('(:num)/file', 'FinalReportsController::getFile/$1');
        $routes->post('(:num)/approval', 'FinalReportsController::approval/$1');
        
        $routes->delete('(:num)', 'FinalReportsController::delete/$1');
    });
    
    $routes->group('attendance', function(RouteCollection $routes) {
        $routes->get('', 'AttendanceController::index');
        $routes->post('check-in', 'AttendanceController::checkIn');
        $routes->post('check-out', 'AttendanceController::checkOut');
        $routes->post('(:num)/verification', 'AttendanceController::verification/$1');
    });
    
    $routes->group('activities', function(RouteCollection $routes) {
        $routes->get('', 'ActivitiesController::index');
        $routes->post('', 'ActivitiesController::store');
        $routes->get('(:num)/file', 'ActivitiesController::getFile/$1');
        $routes->put('(:num)', 'ActivitiesController::update/$1');
        $routes->delete('(:num)', 'ActivitiesController::delete/$1');
    });
});
