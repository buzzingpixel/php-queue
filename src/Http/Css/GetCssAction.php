<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Css;

use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function file_get_contents;

readonly class GetCssAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/style.css',
            self::class,
        );
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) file_get_contents(
            CssPath::CSS_FILE_PATH,
        ));

        return $response->withHeader('Content-type', 'text/css');
    }
}
