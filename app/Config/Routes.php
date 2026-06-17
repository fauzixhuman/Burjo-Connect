<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'CustomerController::menu');
$routes->get('/checkout', 'CustomerController::checkout');
$routes->get('/track/(:num)', 'CustomerController::track/$1');

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('menus', 'CheckoutController::getMenus');
    $routes->post('checkout', 'CheckoutController::processCheckout');
    $routes->get('track/(:num)', 'CheckoutController::getOrderStatus/$1');
});

// Admin Auth Routes
$routes->get('admin/login', 'AuthController::login');
$routes->post('admin/process-login', 'AuthController::processLogin');
$routes->get('admin/logout', 'AuthController::logout');

// Admin Protected Routes
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('orders', 'AdminController::orders');
    $routes->get('orders/(:num)/detail', 'AdminController::orderDetail/$1');
    $routes->get('orders/(:num)/print', 'AdminController::printOrder/$1');
    $routes->post('orders/(:num)/status', 'AdminController::updateOrderStatus/$1');
    $routes->post('transactions/(:num)/mark-paid', 'AdminController::markTransactionPaid/$1');
    $routes->get('menus', 'AdminController::menus');
    $routes->post('menus/create', 'AdminController::createMenu');
    $routes->post('menus/update/(:num)', 'AdminController::updateMenu/$1');
    $routes->post('menus/delete/(:num)', 'AdminController::deleteMenu/$1');
});
