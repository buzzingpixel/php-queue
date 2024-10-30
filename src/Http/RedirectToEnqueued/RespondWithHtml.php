<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\RedirectToEnqueued;

use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(private RoutesFactory $routesFactory)
    {
    }

    public function respond(ResponseInterface $response): ResponseInterface
    {
        $routes = $this->routesFactory->create();

        return $response->withStatus(302)
            ->withHeader(
                'Location',
                $routes->pluckClassName(
                    GetEnqueuedAction::class,
                )->pattern,
            );
    }
}
