<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers'); // Set the default namespace for controllers.
$routes->setDefaultController('Home');          // Set the default controller.
$routes->setDefaultMethod('index');             // Set the default method.
$routes->setTranslateURIDashes(false);          // Disable translation of dashes to underscores in URIs.
$routes->set404Override(function () {           // Set a custom 404 handler.
    echo view('errors/html/error_404');         // Replace this with your own logic or view.
});
$routes->setAutoRoute(true);                    // Enable auto-routing.



/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Define your routes here. For example:
$routes->get('/', 'HomeController::index');               // Default route for the root URL.
$routes->get('/register', 'AuthController::registerForm'); // Show the registration form
$routes->post('/register', 'AuthController::register');    // Handle registration submission when click button

$routes->get('/login', 'AuthController::loginForm');       // Show the login form
$routes->post('/login', 'AuthController::login');          // Handle login submission when click button

$routes->get('/homepage', 'HomeController::index');     // Display the homepage
$routes->get('/logout', 'AuthController::logout');


// Admin Dashboard
$routes->get('/dashboard', 'AdminController::dashboard', ['filter' => 'rolecheck:admin']); // Display admin dashboard

// Update User Role
$routes->post('/update-role/(:num)', 'AdminController::updateRole/$1', ['filter' => 'rolecheck:admin']); // Update user role
$routes->post('/update-role-permissions', 'AdminController::updateRolePermissions', ['filter' => 'rolecheck:superadmin']);

$routes->get('/unauthorized', function () {
    return view('errors/unauthorized');
});
$routes->get('/profile', 'UserController::profile');
$routes->get('/dashboard', 'AdminController::dashboard', ['filter' => 'rolecheck:admin,superadmin']);

$routes->group('labs', ['filter' => 'permission:Manage Labs'], function ($routes) {
    $routes->get('create', 'LabController::create');
    $routes->post('store', 'LabController::store');
    $routes->get('edit/(:num)', 'LabController::edit/$1');
    $routes->post('update/(:num)', 'LabController::update/$1');
    $routes->get('/delete/(:num)', 'LabController::delete/$1');
    /*$routes->post('delete/(:num)', 'LabController::delete/$1');*/
});

$routes->get('/labs', 'HomeController::index');
$routes->get('/labs/filter', 'HomeController::filterLabs');
$routes->get('labs/delete/(:num)', 'LabController::delete/$1');

$routes->get('/labs/view/(:num)', 'LabController::view/$1');
$routes->get('/labs/view/(:num)/inventory', 'LabController::inventory/$1');
$routes->get('/labs/view/(:num)/borrowing-log', 'LabController::borrowingLog/$1');
$routes->get('/labs/view/(:num)/analytics', 'LabController::analytics/$1');

$routes->post('/labs/(:num)/components/add', 'LabController::addComponent/$1');
$routes->get('/components/delete/(:num)', 'LabController::deleteComponent/$1');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * This is where you can include additional routing files if needed.
 * For example, you might separate routing by module.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
