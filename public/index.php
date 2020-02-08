<?php
require_once('../vendor/autoload.php');

session_start();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response\RedirectResponse;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
  $_SERVER,
  $_GET,
  $_POST,
  $_COOKIE,
  $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/', [
  'controller' => 'App\Controllers\IndexController',
  'action' => 'indexAction'
]);

$map->get('addJob', '/jobs/add', [
  'controller' => 'App\Controllers\JobController',
  'action' => 'getAddJob',
  'auth' => true
]);
$map->post('saveJob', '/jobs/add', [
  'controller' => 'App\Controllers\JobController',
  'action' => 'postSaveJob',
  'auth' => true
]);

$map->get('addProject', '/projects/add', [
  'controller' => 'App\Controllers\ProjectController',
  'action' => 'getAddProject',
  'auth' => true
]);
$map->post('saveProject', '/projects/add', [
  'controller' => 'App\Controllers\ProjectController',
  'action' => 'postSaveProject',
  'auth' => true
]);

$map->get('addUser', '/users/add', [
  'controller' => 'App\Controllers\UserController',
  'action' => 'getAddUser',
  'auth' => true
]);
$map->post('saveUser', '/users/add', [
  'controller' => 'App\Controllers\UserController',
  'action' => 'postSaveUser',
  'auth' => true
]);

$map->get('loginForm', '/login', [
  'controller' => 'App\Controllers\AuthController',
  'action' => 'getLogin'
]);
$map->get('logout', '/logout', [
  'controller' => 'App\Controllers\AuthController',
  'action' => 'getLogout'
]);
$map->post('auth', '/auth', [
  'controller' => 'App\Controllers\AuthController',
  'action' => 'postLogin'
]);

$map->get('admin', '/admin', [
  'controller' => 'App\Controllers\AdminController',
  'action' => 'getIndex',
  'auth' => true
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);
if ( !$route )
  echo 'Route not found';
else {
  $controller = new $route->handler['controller'];
  $actionName = $route->handler['action'];
  $needsAuth = $route->handler['auth'] ?? false;

  if ($needsAuth && !isset($_SESSION['userId']))
    $response = new RedirectResponse('/login');
  else
    $response = $controller->$actionName($request);

  foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
      header(sprintf('%s: %s', $name, $value), false);
    }
  }
  http_response_code($response->getStatusCode());
  echo $response->getBody();
}
