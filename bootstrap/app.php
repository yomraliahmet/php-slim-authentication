<?php

use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slim',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]
    ]
    
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
    return $capsule;
};


// view ayarlanıyor.
$container['view'] = function($container){
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

// Validator ayarlanıyor
$container['validator'] = function($container){
    return new App\Validation\Validator;
};


// HomeController ayarlanıyor.
$container['HomeController'] = function($container){
    return new \App\Controllers\HomeController($container);
};

// AuthController ayarlanıyor.
$container['AuthController'] = function($container){
    return new \App\Controllers\Auth\AuthController($container);
};

// Csrf aayarlanıyor
$container['csrf'] = function($container){
    return new \Slim\Csrf\Guard;
};

$app->add($container->csrf);


// Validation ayarlanıyor.
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));

// Old Input Değerleri ayarlanıyor.
$app->add(new \App\Middleware\OldInputMiddleware($container));

// Csrf Middleware ayarlanıyor.
$app->add(new \App\Middleware\CsrfViewMiddleware($container));


// Custom validation ayarlanıyor
v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';

