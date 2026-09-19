<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->get('/', 'Home::index');


$routes->group('api', function($routes) {
    $routes->resource('blog', ['controller' => 'API\Blog']);

    $routes->post('login', 'API\Auth::login');//public route for getting the JWT

    // Private routes group - JWT required
    $routes->group('', ['filter' => 'apiauth'], function($routes) {
        // GET /api/user/profile onlu fetch the data from JWT user
        $routes->get('user/profile', 'API\User::profile');
        $routes->post('user/profile', 'API\User::profile');
    });
});



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
$routes->match(['GET', 'POST'], 'blog/edit/(:num)', 'Blog::edit/$1', ["filter" => 'auth']);
$routes->match(['GET', 'POST'], 'blog/my-posts', 'Blog::myPosts', ["filter" => 'auth']);
$routes->get('/post/(:segment)', 'Blog::post/$1');

$routes->get('/pages/(:segment)', 'Pages::show/$1');
$routes->get('(:any)', 'Pages::show/$1');

//Display custom 404 view
$routes->set404Override(static function () {
    // If you want to get the URI segments.
    //$segments = request()->getUri()->getSegments();

    return view('errors/html/error_404');
});