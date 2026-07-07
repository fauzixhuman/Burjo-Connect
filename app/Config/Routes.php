<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Customer - Halaman Menu
$routes->get('/', 'CustomerController::menu');
$routes->get('/track/(:num)', 'CustomerController::track/$1');

// Cart Routes (menggunakan library CodeIgniterCart dari GitHub)
$routes->get('/cart', 'CartController::index');
$routes->post('/cart/insert', 'CartController::insert');
$routes->post('/cart/update', 'CartController::update');
$routes->post('/cart/remove/(:any)', 'CartController::remove/$1');
$routes->get('/cart/destroy', 'CartController::destroy');
$routes->post('/cart/checkout', 'CartController::checkout');

// API Routes (untuk tracking status pesanan)
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('track/(:num)', 'CheckoutController::getOrderStatus/$1');
});

// Admin Auth Routes
$routes->get('admin/login', 'AuthController::login');
$routes->post('admin/process-login', 'AuthController::processLogin');
$routes->get('admin/logout', 'AuthController::logout');

// PDF Export Route
$routes->get('export-pdf', 'PdfController::exportMenu');

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
