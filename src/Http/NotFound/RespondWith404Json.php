<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\NotFound;

use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWith404Json
{
    public function respond(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write((string) json_encode(
            ['message' => 'Page not found.'],
        ));

        return $response->withHeader(
            'Content-type',
            'application/json',
        )->withStatus(404);
    }
}
