<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');

// User Acounts routes

$routes->get('/users', 'Users::index');
$routes->post('users/save', 'Users::save');
$routes->get('users/edit/(:segment)', 'Users::edit/$1');
$routes->post('users/update', 'Users::update');
$routes->delete('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/fetchRecords', 'Users::fetchRecords');

// Animal routes

$routes->get('/animals', 'Animals::index');
$routes->post('animals/save', 'Animals::save');
$routes->get('animals/edit/(:segment)', 'Animals::edit/$1');
$routes->post('animals/update', 'Animals::update');
$routes->delete('animals/delete/(:num)', 'Animals::delete/$1');
$routes->post('animals/fetchRecords', 'Animals::fetchRecords');

// Cellphones routes

$routes->get('/cellphones', 'Cellphones::index');
$routes->post('cellphones/save', 'Cellphones::save');
$routes->get('cellphones/edit/(:segment)', 'Cellphones::edit/$1');
$routes->post('cellphones/update', 'Cellphones::update');
$routes->delete('cellphones/delete/(:num)', 'Cellphones::delete/$1');
$routes->post('cellphones/fetchRecords', 'Cellphones::fetchRecords');

$routes->get('/foods', 'Foods::index');
$routes->post('foods/save', 'Foods::save');
$routes->get('foods/edit/(:segment)', 'Foods::edit/$1');
$routes->post('foods/update', 'Foods::update');
$routes->delete('foods/delete/(:num)', 'Foods::delete/$1');
$routes->post('foods/fetchRecords', 'Foods::fetchRecords');


// Logs routes for admin
$routes->get('/log', 'Logs::log');