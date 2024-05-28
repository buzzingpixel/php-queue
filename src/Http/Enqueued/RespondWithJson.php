<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\QueueItemWithKeyCollection;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

readonly class RespondWithJson
{
    public function respond(
        QueueItemWithKeyCollection $queueItems,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write((string) json_encode(
            $queueItems->asArray(),
        ));

        return $response->withHeader('Content-type', 'application/json');
    }
}
