<?php

use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteParser;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once 'vendor/autoload.php';
session_start();

// Illuminate/Database connection
$capsule = new Capsule();
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'smsecom',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Route with PHRoute
$router = new RouteCollector(new RouteParser());
require_once __DIR__ . '/routes.php';
$dispatcher = new Dispatcher($router->getData());
try {
    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
} catch (HttpMethodNotAllowedException $e) {
    echo $e->getMessage();
    die();
} catch(HttpRouteNotFoundException $e){
    echo $e->getMessage();
    die();
}
// Print out the value returned from the dispatched function
echo $response;

