<?php

declare(strict_types=1);

namespace App;

use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

readonly class RegisterRoutes
{
    public function register(
        RouteCollectorProxyInterface $routes,
        ContainerInterface $di,
    ): void {
        $routes->get('/', GetHomeAction::class);

        $queueRoutesFactory = $di->get(RoutesFactory::class);
        $queueRoutes = $queueRoutesFactory->create();
        $queueRoutes->map(
            static function (Route $route) use ($routes): void {
                $routes->map(
                    [$route->requestMethod->name],
                    $route->pattern,
                    $route->class,
                );
            },
        );
    }
}
