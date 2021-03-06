<?php

use Phroute\Phroute\RouteCollector;

// create route filter
$router->filter('auth', function () {
    if (!isset($_SESSION['user'])) {
        errMsg("Invalid attempt. You must be authenticated.", "login");
    }
});

$router->controller('/', App\Controllers\Frontend\HomeController::class);
$router->controller('/cart', App\Controllers\Frontend\CartController::class);
$router->group(['before' => 'auth'], function($router){
    $router->controller('/checkout', App\Controllers\Frontend\CheckoutController::class);
});


// route filter applied
$router->group(['before' => 'auth', 'prefix' => 'dashboard'], function (RouteCollector $router) {
    $router->controller('/', App\Controllers\Backend\DashboardController::class);
    $router->controller('/categories', App\Controllers\Backend\CategoryController::class);
    $router->controller('/products', App\Controllers\Backend\ProductController::class);

});

