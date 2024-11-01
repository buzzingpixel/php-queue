<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details\Retry;

use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class PostRetryFailedAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::POST,
            $routePrefix . '/failed/details/{key}/retry',
            self::class,
        );
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write('TODO: Finish this route');

        return $response;
    }
}
