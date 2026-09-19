<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->get('/', 'Home::index');


$routes->get('/', 'Pages::index');//pass home as 1st argument
$routes->match(['GET', 'POST'], '/login', 'Users::index', ["filter" => 'noauth']);
$routes->match(['GET', 'POST'], '/register', 'Users::register', ["filter" => 'noauth']);
$routes->get('/dashboard', 'Dashboard::index', [
    "filter" => 'auth',//custom filter registered @ app/Config/Filters.php
]);
$routes->match(['GET', 'POST'], '/profile', 'Users::profile', [
    "filter" => 'auth',
]);
$routes->match(['GET', 'POST'], '/users/profile', 'Users::profile');//checking if global filters work called in $globals @ app/Config/Filters.php
$routes->match(['GET', 'POST'], '/users/register', 'Users::register');
$routes->match(['GET', 'POST'], '/users/login', 'Users::index');

//Just for testing after filters (applied after entering the controller method)

$routes->get('/logout', 'Users::logout', [
    "filter" => 'auth',
]);

$routes->match(['GET', 'POST'], 'blog/create', 'Blog::create', ["filter" => 'auth']);
$routes->get('/post/(:segment)', 'Blog::post/$1');

$routes->get('/pages/(:segment)', 'Pages::show/$1');
$routes->get('(:any)', 'Pages::show/$1');

//Display custom 404 view
$routes->set404Override(static function () {
    // If you want to get the URI segments.
    //$segments = request()->getUri()->getSegments();

    return view('errors/html/error_404');
});