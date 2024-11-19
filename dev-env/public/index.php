<?php

declare(strict_types=1);

use App\ContainerBindings;
use App\RegisterBindings;
use App\RegisterRoutes;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require __DIR__ . '/../vendor/autoload.php';

// Dev error handling
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$whoops = new WhoopsRun();
$whoops->prependHandler(new PrettyPageHandler());
$whoops->register();

// Create container
$bindings = new ContainerBindings();
(new RegisterBindings())->register($bindings);
$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions($bindings->bindings())
    ->build();

// Create the slim app
AppFactory::setContainer($di);
$app = AppFactory::create();

// Register routes
(new RegisterRoutes)->register($app, $di);

// Run the app
$app->run();
