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
$routes->get('/', 'Home::index');               // Default route for the root URL.
$routes->get('/register', 'AuthController::registerForm'); // Show the registration form
$routes->post('/register', 'AuthController::register');    // Handle registration submission when click button

$routes->get('/login', 'AuthController::loginForm');       // Show the login form
$routes->post('/login', 'AuthController::login');          // Handle login submission when click button

$routes->get('/homepage', 'AuthController::homepage');     // Display the homepage
$routes->get('/logout', 'AuthController::logout');
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
