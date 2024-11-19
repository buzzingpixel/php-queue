<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\RedirectToEnqueued;

use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function __construct(private RoutesFactory $routesFactory)
    {
    }

    public function respond(ResponseInterface $response): ResponseInterface
    {
        $routes = $this->routesFactory->create();

        $response->getBody()->write((string) json_encode(
            [
                'location' => $routes->pluckClassName(
                    GetEnqueuedAction::class,
                )->pattern,
            ],
        ));

        return $response->withHeader(
            'Content-type',
            'application/json',
        );
    }
}
